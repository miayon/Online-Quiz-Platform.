<?php
// models/TAModel.php
require_once __DIR__ . '/../config/db.php';

class TAModel {

    public static function requireAssignedCourse($taId, $courseId) {
        return db_fetch_one(
            "SELECT ct.id FROM course_tas ct WHERE ct.ta_id = ? AND ct.course_id = ? LIMIT 1",
            [$taId, $courseId],
            "ii"
        );
    }

    public static function getProfile($taId) {
        return db_fetch_one(
            "SELECT id, name, email, phone, profile_pic, program, role, is_active FROM users WHERE id = ? AND role = 'ta'",
            [$taId],
            "i"
        );
    }

    public static function updateProfile($taId, $name, $phone, $program, $profilePic = null) {
        if ($profilePic) {
            return db_query(
                "UPDATE users SET name = ?, phone = ?, program = ?, profile_pic = ? WHERE id = ? AND role = 'ta'",
                [$name, $phone, $program, $profilePic, $taId],
                "ssssi"
            );
        }

        return db_query(
            "UPDATE users SET name = ?, phone = ?, program = ? WHERE id = ? AND role = 'ta'",
            [$name, $phone, $program, $taId],
            "sssi"
        );
    }

    public static function getAssignedCourses($taId) {
        return db_fetch_all(
            "SELECT c.*, s.name AS subject_name, u.name AS instructor_name,
                    COUNT(DISTINCT e.student_id) AS total_students,
                    COUNT(DISTINCT q.id) AS total_quizzes,
                    COALESCE(ROUND(AVG(a.score), 2), 0) AS average_score
             FROM course_tas ct
             INNER JOIN courses c ON c.id = ct.course_id
             LEFT JOIN subjects s ON s.id = c.subject_id
             LEFT JOIN users u ON u.id = c.instructor_id
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = 'active'
             LEFT JOIN quizzes q ON q.course_id = c.id
             LEFT JOIN attempts a ON a.quiz_id = q.id
             WHERE ct.ta_id = ?
             GROUP BY c.id
             ORDER BY c.created_at DESC",
            [$taId],
            "i"
        );
    }

    public static function getCourse($taId, $courseId) {
        return db_fetch_one(
            "SELECT c.*, s.name AS subject_name, u.name AS instructor_name
             FROM courses c
             INNER JOIN course_tas ct ON ct.course_id = c.id
             LEFT JOIN subjects s ON s.id = c.subject_id
             LEFT JOIN users u ON u.id = c.instructor_id
             WHERE ct.ta_id = ? AND c.id = ?
             LIMIT 1",
            [$taId, $courseId],
            "ii"
        );
    }

    public static function getCourseStudents($courseId) {
        return db_fetch_all(
            "SELECT u.id, u.name, u.email, u.student_id, u.program, e.status, e.enrolled_at
             FROM enrollments e
             INNER JOIN users u ON u.id = e.student_id
             WHERE e.course_id = ?
             ORDER BY u.name ASC",
            [$courseId],
            "i"
        );
    }

    public static function getCourseQuizzes($courseId) {
        return db_fetch_all(
            "SELECT q.*, u.name AS creator_name
             FROM quizzes q
             LEFT JOIN users u ON u.id = q.created_by
             WHERE q.course_id = ?
             ORDER BY q.id DESC",
            [$courseId],
            "i"
        );
    }

    public static function createPracticeQuiz($courseId, $taId, $title, $description, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableUntil) {
        return db_query(
            "INSERT INTO quizzes
             (course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'practice', 'pending_approval', ?, ?)",
            [$courseId, $taId, $title, $description, $timeLimit, $totalMarks, $passMark, $availableFrom, $availableUntil],
            "iissiiiss"
        );
    }

    public static function quizBelongsToCourse($quizId, $courseId) {
        return db_fetch_one(
            "SELECT id FROM quizzes WHERE id = ? AND course_id = ? LIMIT 1",
            [$quizId, $courseId],
            "ii"
        );
    }

    public static function addQuestion($quizId, $taId, $questionText, $marks, $orderIndex, $options, $correctOption) {
        global $conn;

        $conn->begin_transaction();

        try {
            db_query(
                "INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by)
                 VALUES (?, ?, ?, ?, ?)",
                [$quizId, $questionText, $marks, $orderIndex, $taId],
                "isiii"
            );

            $questionId = $conn->insert_id;

            foreach ($options as $index => $optionText) {
                $isCorrect = ($index + 1 == $correctOption) ? 1 : 0;
                db_query(
                    "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
                    [$questionId, $optionText, $isCorrect],
                    "isi"
                );
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function getQuestions($courseId) {
        return db_fetch_all(
            "SELECT qu.*, q.title AS quiz_title
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             WHERE q.course_id = ?
             ORDER BY q.id DESC, qu.order_index ASC",
            [$courseId],
            "i"
        );
    }

    public static function getQuestionOptions($questionId) {
        return db_fetch_all(
            "SELECT * FROM options WHERE question_id = ? ORDER BY id ASC",
            [$questionId],
            "i"
        );
    }

    public static function updateQuestion($questionId, $courseId, $questionText, $marks, $orderIndex, $options, $correctOption) {
        global $conn;

        $check = db_fetch_one(
            "SELECT qu.id
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             WHERE qu.id = ? AND q.course_id = ?
             LIMIT 1",
            [$questionId, $courseId],
            "ii"
        );

        if (!$check) return false;

        $conn->begin_transaction();

        try {
            db_query(
                "UPDATE questions SET question_text = ?, marks = ?, order_index = ? WHERE id = ?",
                [$questionText, $marks, $orderIndex, $questionId],
                "siii"
            );

            db_query("DELETE FROM options WHERE question_id = ?", [$questionId], "i");

            foreach ($options as $index => $optionText) {
                $isCorrect = ($index + 1 == $correctOption) ? 1 : 0;
                db_query(
                    "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
                    [$questionId, $optionText, $isCorrect],
                    "isi"
                );
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function deleteQuestion($questionId, $courseId) {
        global $conn;

        $check = db_fetch_one(
            "SELECT qu.id
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             WHERE qu.id = ? AND q.course_id = ?
             LIMIT 1",
            [$questionId, $courseId],
            "ii"
        );

        if (!$check) return false;

        $conn->begin_transaction();

        try {
            db_query("DELETE FROM options WHERE question_id = ?", [$questionId], "i");
            db_query("DELETE FROM questions WHERE id = ?", [$questionId], "i");
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function getAttemptResults($courseId) {
        return db_fetch_all(
            "SELECT a.id AS attempt_id, a.score, a.started_at, a.completed_at, a.is_graded,
                    u.id AS student_id, u.name AS student_name, u.email AS student_email,
                    q.title AS quiz_title, q.total_marks, q.pass_mark,
                    EXISTS(SELECT 1 FROM ta_student_flags f WHERE f.attempt_id = a.id) AS flagged
             FROM attempts a
             INNER JOIN quizzes q ON q.id = a.quiz_id
             INNER JOIN users u ON u.id = a.student_id
             WHERE q.course_id = ?
             ORDER BY a.completed_at DESC, a.started_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function getAtRiskStudents($courseId, $threshold) {
        return db_fetch_all(
            "SELECT a.id AS attempt_id, a.score, a.completed_at,
                    u.id AS student_id, u.name AS student_name, u.email AS student_email,
                    q.title AS quiz_title, q.total_marks
             FROM attempts a
             INNER JOIN quizzes q ON q.id = a.quiz_id
             INNER JOIN users u ON u.id = a.student_id
             WHERE q.course_id = ? AND a.score < ?
             ORDER BY a.score ASC",
            [$courseId, $threshold],
            "ii"
        );
    }

    public static function flagStudent($courseId, $studentId, $attemptId, $taId, $reason) {
        return db_query(
            "INSERT INTO ta_student_flags (course_id, student_id, attempt_id, ta_id, reason, status)
             VALUES (?, ?, ?, ?, ?, 'pending_review')",
            [$courseId, $studentId, $attemptId, $taId, $reason],
            "iiiis"
        );
    }

    public static function getSetting($key, $default = null) {
        $row = db_fetch_one(
            "SELECT setting_value FROM platform_settings WHERE setting_key = ? LIMIT 1",
            [$key],
            "s"
        );

        return $row ? $row['setting_value'] : $default;
    }

    public static function setSetting($key, $value) {
        return db_query(
            "INSERT INTO platform_settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
            [$key, $value],
            "ss"
        );
    }

    public static function createAnnouncement($courseId, $taId, $title, $body) {
        return db_query(
            "INSERT INTO announcements (course_id, author_id, title, body) VALUES (?, ?, ?, ?)",
            [$courseId, $taId, $title, "[From TA] " . $body],
            "iiss"
        );
    }

    public static function getAnnouncements($courseId) {
        return db_fetch_all(
            "SELECT a.*, u.name AS author_name
             FROM announcements a
             LEFT JOIN users u ON u.id = a.author_id
             WHERE a.course_id = ?
             ORDER BY a.created_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function addMaterial($courseId, $taId, $title, $filePath, $materialType) {
        return db_query(
            "INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type)
             VALUES (?, ?, ?, ?, ?)",
            [$courseId, $taId, $title, $filePath, $materialType],
            "iisss"
        );
    }

    public static function getMaterials($courseId) {
        return db_fetch_all(
            "SELECT m.*, u.name AS uploaded_by_name
             FROM course_materials m
             LEFT JOIN users u ON u.id = m.uploaded_by
             WHERE m.course_id = ?
             ORDER BY m.created_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function updateOwnMaterial($materialId, $courseId, $taId, $title, $filePath, $materialType) {
        return db_query(
            "UPDATE course_materials
             SET title = ?, file_path = ?, material_type = ?
             WHERE id = ? AND course_id = ? AND uploaded_by = ?",
            [$title, $filePath, $materialType, $materialId, $courseId, $taId],
            "sssiii"
        );
    }

    public static function deleteOwnMaterial($materialId, $courseId, $taId) {
        return db_query(
            "DELETE FROM course_materials WHERE id = ? AND course_id = ? AND uploaded_by = ?",
            [$materialId, $courseId, $taId],
            "iii"
        );
    }

    public static function getQAQuestions($courseId) {
        return db_fetch_all(
            "SELECT q.*, u.name AS student_name
             FROM qa_questions q
             LEFT JOIN users u ON u.id = q.student_id
             WHERE q.course_id = ?
             ORDER BY q.created_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function getQAAnswers($qaQuestionId) {
        return db_fetch_all(
            "SELECT a.*, u.name AS author_name
             FROM qa_answers a
             LEFT JOIN users u ON u.id = a.author_id
             WHERE a.qa_question_id = ?
             ORDER BY a.created_at ASC",
            [$qaQuestionId],
            "i"
        );
    }

    public static function answerQA($qaQuestionId, $taId, $body) {
        return db_query(
            "INSERT INTO qa_answers (qa_question_id, author_id, body) VALUES (?, ?, ?)",
            [$qaQuestionId, $taId, $body],
            "iis"
        );
    }

    public static function endorseAnswer($answerId, $courseId) {
        return db_query(
            "UPDATE qa_answers a
             INNER JOIN qa_questions q ON q.id = a.qa_question_id
             SET a.is_endorsed = 1
             WHERE a.id = ? AND q.course_id = ?",
            [$answerId, $courseId],
            "ii"
        );
    }

    public static function resolveQuestion($qaQuestionId, $courseId) {
        return db_query(
            "UPDATE qa_questions SET is_resolved = 1 WHERE id = ? AND course_id = ?",
            [$qaQuestionId, $courseId],
            "ii"
        );
    }

    public static function createDoubtSession($courseId, $taId, $title, $scheduledAt, $duration, $location, $maxAttendees) {
        global $conn;

        $conn->begin_transaction();

        try {
            db_query(
                "INSERT INTO doubt_sessions (course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees)
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$courseId, $taId, $title, $scheduledAt, $duration, $location, $maxAttendees],
                "iissisi"
            );

            $sessionId = $conn->insert_id;

            db_query(
                "INSERT INTO ta_doubt_session_status (doubt_session_id, status, notice)
                 VALUES (?, 'scheduled', '')",
                [$sessionId],
                "i"
            );

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    public static function getDoubtSessions($courseId, $taId) {
        return db_fetch_all(
            "SELECT ds.*, COALESCE(st.status, 'scheduled') AS session_status, COALESCE(st.notice, '') AS notice
             FROM doubt_sessions ds
             LEFT JOIN ta_doubt_session_status st ON st.doubt_session_id = ds.id
             WHERE ds.course_id = ? AND ds.ta_id = ?
             ORDER BY ds.scheduled_at DESC",
            [$courseId, $taId],
            "ii"
        );
    }

    public static function getSessionBookings($sessionId) {
        return db_fetch_all(
            "SELECT b.*, u.name AS student_name, u.email AS student_email
             FROM doubt_session_bookings b
             INNER JOIN users u ON u.id = b.student_id
             WHERE b.doubt_session_id = ?
             ORDER BY b.booked_at ASC",
            [$sessionId],
            "i"
        );
    }

    public static function updateSessionStatus($sessionId, $courseId, $taId, $status, $notice, $scheduledAt = null, $duration = null, $location = null) {
        if ($scheduledAt && $duration && $location) {
            db_query(
                "UPDATE doubt_sessions
                 SET scheduled_at = ?, duration_minutes = ?, location_or_link = ?
                 WHERE id = ? AND course_id = ? AND ta_id = ?",
                [$scheduledAt, $duration, $location, $sessionId, $courseId, $taId],
                "sisiii"
            );
        }

        return db_query(
            "INSERT INTO ta_doubt_session_status (doubt_session_id, status, notice)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE status = VALUES(status), notice = VALUES(notice), updated_at = CURRENT_TIMESTAMP",
            [$sessionId, $status, $notice],
            "iss"
        );
    }

    public static function getCourseReport($courseId, $threshold) {
        return db_fetch_one(
            "SELECT
                COUNT(DISTINCT e.student_id) AS total_students,
                COUNT(DISTINCT q.id) AS total_quizzes,
                COUNT(DISTINCT a.id) AS total_attempts,
                COUNT(DISTINCT CASE WHEN a.score < ? THEN a.student_id END) AS at_risk_students,
                COALESCE(ROUND(AVG(a.score), 2), 0) AS average_score,
                COUNT(DISTINCT m.id) AS total_materials,
                COUNT(DISTINCT qa.id) AS total_qa,
                COUNT(DISTINCT ds.id) AS total_sessions
             FROM courses c
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = 'active'
             LEFT JOIN quizzes q ON q.course_id = c.id
             LEFT JOIN attempts a ON a.quiz_id = q.id
             LEFT JOIN course_materials m ON m.course_id = c.id
             LEFT JOIN qa_questions qa ON qa.course_id = c.id
             LEFT JOIN doubt_sessions ds ON ds.course_id = c.id
             WHERE c.id = ?",
            [$threshold, $courseId],
            "ii"
        );
    }
}
?>