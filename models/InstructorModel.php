<?php
// models/InstructorModel.php
require_once __DIR__ . "/../config/db.php";

class InstructorModel {
    public static function getInstructor($instructorId) {
        return db_fetch_one("SELECT * FROM users WHERE id = ? AND role = 'instructor' LIMIT 1", [$instructorId], "i");
    }

    public static function updateProfile($instructorId, $name, $phone, $department, $bio, $profilePic = null) {
        if ($profilePic) {
            return db_query(
                "UPDATE users SET name = ?, phone = ?, program = ?, bio = ?, profile_pic = ? WHERE id = ? AND role = 'instructor'",
                [$name, $phone, $department, $bio, $profilePic, $instructorId],
                "sssssi"
            );
        }

        return db_query(
            "UPDATE users SET name = ?, phone = ?, program = ?, bio = ? WHERE id = ? AND role = 'instructor'",
            [$name, $phone, $department, $bio, $instructorId],
            "ssssi"
        );
    }

    public static function ownsCourse($instructorId, $courseId) {
        return db_fetch_one("SELECT id FROM courses WHERE id = ? AND instructor_id = ? LIMIT 1", [$courseId, $instructorId], "ii");
    }

    public static function ownsQuiz($instructorId, $quizId) {
        return db_fetch_one(
            "SELECT q.id FROM quizzes q INNER JOIN courses c ON c.id = q.course_id WHERE q.id = ? AND c.instructor_id = ? LIMIT 1",
            [$quizId, $instructorId],
            "ii"
        );
    }

    public static function getSubjects() {
        return db_fetch_all("SELECT * FROM subjects ORDER BY name ASC");
    }

    public static function getTAAccounts() {
        return db_fetch_all("SELECT id, name, email, program FROM users WHERE role = 'ta' AND is_active = 1 ORDER BY name ASC");
    }

    public static function getCourses($instructorId) {
        return db_fetch_all(
            "SELECT c.*, s.name AS subject_name,
                    COUNT(DISTINCT e.student_id) AS enrolled_students,
                    COUNT(DISTINCT q.id) AS total_quizzes
             FROM courses c
             LEFT JOIN subjects s ON s.id = c.subject_id
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = 'active'
             LEFT JOIN quizzes q ON q.course_id = c.id
             WHERE c.instructor_id = ?
             GROUP BY c.id
             ORDER BY c.created_at DESC",
            [$instructorId],
            "i"
        );
    }

    public static function getCourse($instructorId, $courseId) {
        return db_fetch_one(
            "SELECT c.*, s.name AS subject_name
             FROM courses c
             LEFT JOIN subjects s ON s.id = c.subject_id
             WHERE c.id = ? AND c.instructor_id = ?
             LIMIT 1",
            [$courseId, $instructorId],
            "ii"
        );
    }

    public static function createCourse($instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status) {
        return db_query(
            "INSERT INTO courses (instructor_id, subject_id, title, description, enrollment_type, max_students, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status],
            "iisssis"
        );
    }

    public static function updateCourse($courseId, $instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status) {
        return db_query(
            "UPDATE courses
             SET subject_id = ?, title = ?, description = ?, enrollment_type = ?, max_students = ?, status = ?
             WHERE id = ? AND instructor_id = ?",
            [$subjectId, $title, $description, $enrollmentType, $maxStudents, $status, $courseId, $instructorId],
            "isssisii"
        );
    }

    public static function archiveCourse($courseId, $instructorId) {
        return db_query(
            "UPDATE courses SET status = 'archived' WHERE id = ? AND instructor_id = ?",
            [$courseId, $instructorId],
            "ii"
        );
    }

    public static function getEnrolledStudents($courseId) {
        return db_fetch_all(
            "SELECT e.*, u.name, u.email, u.student_id, u.program
             FROM enrollments e
             INNER JOIN users u ON u.id = e.student_id
             WHERE e.course_id = ?
             ORDER BY e.enrolled_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function getEnrollmentRequests($courseId) {
        return db_fetch_all(
            "SELECT e.*, u.name, u.email, u.student_id, u.program
             FROM enrollments e
             INNER JOIN users u ON u.id = e.student_id
             WHERE e.course_id = ? AND e.status = 'pending'
             ORDER BY e.enrolled_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function updateEnrollmentStatus($enrollmentId, $courseId, $status) {
        return db_query(
            "UPDATE enrollments SET status = ? WHERE id = ? AND course_id = ?",
            [$status, $enrollmentId, $courseId],
            "sii"
        );
    }

    public static function getAssignedTAs($courseId) {
        return db_fetch_all(
            "SELECT ct.*, u.name, u.email, u.program
             FROM course_tas ct
             INNER JOIN users u ON u.id = ct.ta_id
             WHERE ct.course_id = ?
             ORDER BY ct.assigned_at DESC",
            [$courseId],
            "i"
        );
    }

    public static function assignTA($courseId, $taId) {
        $exists = db_fetch_one("SELECT id FROM course_tas WHERE course_id = ? AND ta_id = ? LIMIT 1", [$courseId, $taId], "ii");

        if ($exists) {
            return true;
        }

        return db_query("INSERT INTO course_tas (course_id, ta_id) VALUES (?, ?)", [$courseId, $taId], "ii");
    }

    public static function removeTA($courseId, $taId) {
        return db_query("DELETE FROM course_tas WHERE course_id = ? AND ta_id = ?", [$courseId, $taId], "ii");
    }

    public static function getQuizzes($instructorId, $courseId = 0) {
        if ($courseId > 0) {
            return db_fetch_all(
                "SELECT q.*, c.title AS course_title,
                        COUNT(DISTINCT a.id) AS attempt_count,
                        COALESCE(ROUND(AVG(a.score), 2), 0) AS average_score
                 FROM quizzes q
                 INNER JOIN courses c ON c.id = q.course_id
                 LEFT JOIN attempts a ON a.quiz_id = q.id
                 WHERE c.instructor_id = ? AND q.course_id = ?
                 GROUP BY q.id
                 ORDER BY q.id DESC",
                [$instructorId, $courseId],
                "ii"
            );
        }

        return db_fetch_all(
            "SELECT q.*, c.title AS course_title,
                    COUNT(DISTINCT a.id) AS attempt_count,
                    COALESCE(ROUND(AVG(a.score), 2), 0) AS average_score
             FROM quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             LEFT JOIN attempts a ON a.quiz_id = q.id
             WHERE c.instructor_id = ?
             GROUP BY q.id
             ORDER BY q.id DESC",
            [$instructorId],
            "i"
        );
    }

    public static function getQuiz($instructorId, $quizId) {
        return db_fetch_one(
            "SELECT q.*, c.title AS course_title, c.instructor_id
             FROM quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             WHERE q.id = ? AND c.instructor_id = ?
             LIMIT 1",
            [$quizId, $instructorId],
            "ii"
        );
    }

    public static function createQuiz($courseId, $instructorId, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil) {
        return db_query(
            "INSERT INTO quizzes (course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$courseId, $instructorId, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil],
            "iissiiissss"
        );
    }

    public static function updateQuiz($quizId, $instructorId, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil) {
        return db_query(
            "UPDATE quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             SET q.title = ?, q.description = ?, q.time_limit_minutes = ?, q.total_marks = ?, q.pass_mark = ?, q.quiz_type = ?, q.status = ?, q.available_from = ?, q.available_until = ?
             WHERE q.id = ? AND c.instructor_id = ?",
            [$title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil, $quizId, $instructorId],
            "ssiiissssii"
        );
    }

    public static function toggleQuizStatus($quizId, $instructorId, $status) {
        return db_query(
            "UPDATE quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             SET q.status = ?
             WHERE q.id = ? AND c.instructor_id = ?",
            [$status, $quizId, $instructorId],
            "sii"
        );
    }

    public static function getQuestions($instructorId, $courseId = 0, $quizId = 0) {
        $params = [$instructorId];
        $types = "i";
        $where = "c.instructor_id = ?";

        if ($courseId > 0) {
            $where .= " AND c.id = ?";
            $params[] = $courseId;
            $types .= "i";
        }

        if ($quizId > 0) {
            $where .= " AND q.id = ?";
            $params[] = $quizId;
            $types .= "i";
        }

        return db_fetch_all(
            "SELECT qu.*, q.title AS quiz_title, c.title AS course_title, c.id AS course_id
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             WHERE $where
             ORDER BY c.title ASC, q.title ASC, qu.order_index ASC",
            $params,
            $types
        );
    }

    public static function getQuestionOptions($questionId) {
        return db_fetch_all("SELECT * FROM options WHERE question_id = ? ORDER BY id ASC", [$questionId], "i");
    }

    public static function addQuestion($quizId, $instructorId, $questionText, $marks, $orderIndex, $options, $correctOption) {
        global $conn;
        $conn->begin_transaction();

        try {
            db_query(
                "INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by) VALUES (?, ?, ?, ?, ?)",
                [$quizId, $questionText, $marks, $orderIndex, $instructorId],
                "isiii"
            );

            $questionId = $conn->insert_id;

            foreach ($options as $index => $optionText) {
                $isCorrect = ($index + 1 == $correctOption) ? 1 : 0;
                db_query("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)", [$questionId, $optionText, $isCorrect], "isi");
            }

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public static function updateQuestion($questionId, $instructorId, $questionText, $marks, $orderIndex, $options, $correctOption) {
        global $conn;

        $check = db_fetch_one(
            "SELECT qu.id
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             WHERE qu.id = ? AND c.instructor_id = ?
             LIMIT 1",
            [$questionId, $instructorId],
            "ii"
        );

        if (!$check) {
            throw new Exception("Invalid question.");
        }

        $conn->begin_transaction();

        try {
            db_query("UPDATE questions SET question_text = ?, marks = ?, order_index = ? WHERE id = ?", [$questionText, $marks, $orderIndex, $questionId], "siii");
            db_query("DELETE FROM options WHERE question_id = ?", [$questionId], "i");

            foreach ($options as $index => $optionText) {
                $isCorrect = ($index + 1 == $correctOption) ? 1 : 0;
                db_query("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)", [$questionId, $optionText, $isCorrect], "isi");
            }

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public static function deleteQuestion($questionId, $instructorId) {
        $check = db_fetch_one(
            "SELECT qu.id
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             WHERE qu.id = ? AND c.instructor_id = ?
             LIMIT 1",
            [$questionId, $instructorId],
            "ii"
        );

        if (!$check) {
            throw new Exception("Invalid question.");
        }

        db_query("DELETE FROM options WHERE question_id = ?", [$questionId], "i");
        return db_query("DELETE FROM questions WHERE id = ?", [$questionId], "i");
    }

    public static function reuseQuestion($sourceQuestionId, $targetQuizId, $instructorId) {
        global $conn;

        $source = db_fetch_one(
            "SELECT qu.*
             FROM questions qu
             INNER JOIN quizzes q ON q.id = qu.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             WHERE qu.id = ? AND c.instructor_id = ?
             LIMIT 1",
            [$sourceQuestionId, $instructorId],
            "ii"
        );

        if (!$source) {
            throw new Exception("Source question not found.");
        }

        if (!self::ownsQuiz($instructorId, $targetQuizId)) {
            throw new Exception("Invalid target quiz.");
        }

        $options = self::getQuestionOptions($sourceQuestionId);

        $conn->begin_transaction();

        try {
            db_query(
                "INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by)
                 VALUES (?, ?, ?, ?, ?)",
                [$targetQuizId, $source["question_text"], $source["marks"], $source["order_index"], $instructorId],
                "isiii"
            );

            $newQuestionId = $conn->insert_id;

            foreach ($options as $option) {
                db_query(
                    "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
                    [$newQuestionId, $option["option_text"], $option["is_correct"]],
                    "isi"
                );
            }

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public static function getQuizAttempts($instructorId, $quizId) {
        return db_fetch_all(
            "SELECT a.*, u.name AS student_name, u.email AS student_email, u.student_id,
                    q.title AS quiz_title, q.total_marks, q.pass_mark,
                    TIMESTAMPDIFF(MINUTE, a.started_at, a.completed_at) AS duration_minutes
             FROM attempts a
             INNER JOIN quizzes q ON q.id = a.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             INNER JOIN users u ON u.id = a.student_id
             WHERE q.id = ? AND c.instructor_id = ?
             ORDER BY a.completed_at DESC, a.started_at DESC",
            [$quizId, $instructorId],
            "ii"
        );
    }

    public static function getQuizAnalytics($instructorId, $quizId) {
        $summary = db_fetch_one(
            "SELECT q.title, q.total_marks, q.pass_mark,
                    COUNT(a.id) AS total_attempts,
                    COALESCE(ROUND(AVG(a.score), 2), 0) AS class_average,
                    COALESCE(MAX(a.score), 0) AS highest_score,
                    COALESCE(MIN(a.score), 0) AS lowest_score,
                    SUM(CASE WHEN a.score >= q.pass_mark THEN 1 ELSE 0 END) AS passed_count
             FROM quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             LEFT JOIN attempts a ON a.quiz_id = q.id
             WHERE q.id = ? AND c.instructor_id = ?
             GROUP BY q.id",
            [$quizId, $instructorId],
            "ii"
        );

        if (!$summary) {
            return null;
        }

        $total = max(1, (int)$summary["total_attempts"]);
        $summary["pass_rate"] = round(((int)$summary["passed_count"] / $total) * 100, 2);

        $distribution = db_fetch_all(
            "SELECT
                SUM(CASE WHEN a.score BETWEEN 0 AND 40 THEN 1 ELSE 0 END) AS range_0_40,
                SUM(CASE WHEN a.score BETWEEN 41 AND 60 THEN 1 ELSE 0 END) AS range_41_60,
                SUM(CASE WHEN a.score BETWEEN 61 AND 80 THEN 1 ELSE 0 END) AS range_61_80,
                SUM(CASE WHEN a.score BETWEEN 81 AND 100 THEN 1 ELSE 0 END) AS range_81_100
             FROM attempts a
             INNER JOIN quizzes q ON q.id = a.quiz_id
             INNER JOIN courses c ON c.id = q.course_id
             WHERE q.id = ? AND c.instructor_id = ?",
            [$quizId, $instructorId],
            "ii"
        );

        $summary["distribution"] = $distribution[0] ?? [
            "range_0_40" => 0,
            "range_41_60" => 0,
            "range_61_80" => 0,
            "range_81_100" => 0
        ];

        return $summary;
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

    public static function postAnnouncement($courseId, $instructorId, $title, $body) {
        return db_query(
            "INSERT INTO announcements (course_id, author_id, title, body) VALUES (?, ?, ?, ?)",
            [$courseId, $instructorId, $title, $body],
            "iiss"
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

    public static function addMaterial($courseId, $instructorId, $title, $filePath, $materialType) {
        return db_query(
            "INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type)
             VALUES (?, ?, ?, ?, ?)",
            [$courseId, $instructorId, $title, $filePath, $materialType],
            "iisss"
        );
    }

    public static function updateMaterial($materialId, $courseId, $instructorId, $title, $filePath, $materialType) {
        return db_query(
            "UPDATE course_materials
             SET title = ?, file_path = ?, material_type = ?
             WHERE id = ? AND course_id = ? AND uploaded_by = ?",
            [$title, $filePath, $materialType, $materialId, $courseId, $instructorId],
            "sssiii"
        );
    }

    public static function deleteMaterial($materialId, $courseId, $instructorId) {
        return db_query(
            "DELETE FROM course_materials WHERE id = ? AND course_id = ? AND uploaded_by = ?",
            [$materialId, $courseId, $instructorId],
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

    public static function answerQA($qaQuestionId, $instructorId, $body) {
        return db_query(
            "INSERT INTO qa_answers (qa_question_id, author_id, body) VALUES (?, ?, ?)",
            [$qaQuestionId, $instructorId, $body],
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

    public static function getCourseReport($instructorId, $courseId) {
        return db_fetch_one(
            "SELECT c.id, c.title,
                    COUNT(DISTINCT e.student_id) AS total_enrolled,
                    COUNT(DISTINCT CASE WHEN e.status = 'dropped' THEN e.student_id END) AS dropped_students,
                    COUNT(DISTINCT q.id) AS total_quizzes,
                    COUNT(DISTINCT a.id) AS total_attempts,
                    COALESCE(ROUND(AVG(a.score), 2), 0) AS overall_average
             FROM courses c
             LEFT JOIN enrollments e ON e.course_id = c.id
             LEFT JOIN quizzes q ON q.course_id = c.id
             LEFT JOIN attempts a ON a.quiz_id = q.id
             WHERE c.id = ? AND c.instructor_id = ?
             GROUP BY c.id",
            [$courseId, $instructorId],
            "ii"
        );
    }

    public static function getQuizReportRows($instructorId, $courseId) {
        return db_fetch_all(
            "SELECT q.id, q.title, q.total_marks,
                    COUNT(DISTINCT a.student_id) AS attempted_students,
                    COUNT(DISTINCT e.student_id) AS enrolled_students,
                    COALESCE(ROUND(AVG(a.score), 2), 0) AS average_score
             FROM quizzes q
             INNER JOIN courses c ON c.id = q.course_id
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = 'active'
             LEFT JOIN attempts a ON a.quiz_id = q.id
             WHERE q.course_id = ? AND c.instructor_id = ?
             GROUP BY q.id
             ORDER BY q.id DESC",
            [$courseId, $instructorId],
            "ii"
        );
    }
}
?>
