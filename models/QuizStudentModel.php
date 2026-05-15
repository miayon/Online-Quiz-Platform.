<?php

class QuizStudentModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getQuizzes($student_id) {

        $stmt = $this->conn->prepare("
            SELECT
                q.*,
                c.title AS course_title

            FROM quizzes q

            JOIN courses c
            ON c.id = q.course_id

            JOIN enrollments e
            ON e.course_id = c.id

            WHERE e.student_id = ?
            AND e.status = 'active'
            AND q.status = 'published'

            ORDER BY q.available_from DESC
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function getQuiz(
        $quiz_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM quizzes
            WHERE id = ?
        ");

        $stmt->bind_param(
            "i",
            $quiz_id
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function getQuestions(
        $quiz_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM questions
            WHERE quiz_id = ?
            ORDER BY order_index ASC
        ");

        $stmt->bind_param(
            "i",
            $quiz_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function getOptions(
        $question_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM options
            WHERE question_id = ?
        ");

        $stmt->bind_param(
            "i",
            $question_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function saveAttempt(
        $quiz_id,
        $student_id,
        $score
    ) {

        $stmt = $this->conn->prepare("
            INSERT INTO attempts
            (
                quiz_id,
                student_id,
                score,
                completed_at
            )
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "iii",
            $quiz_id,
            $student_id,
            $score
        );

        $stmt->execute();

        return $this->conn->insert_id;
    }

    public function saveAnswer(
        $attempt_id,
        $question_id,
        $selected_option_id
    ) {

        $stmt = $this->conn->prepare("
            INSERT INTO answers
            (
                attempt_id,
                question_id,
                selected_option_id
            )
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iii",
            $attempt_id,
            $question_id,
            $selected_option_id
        );

        return $stmt->execute();
    }

    public function checkCorrect(
        $question_id,
        $option_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                q.marks
            FROM options o

            JOIN questions q
            ON q.id = o.question_id

            WHERE o.id = ?
            AND o.question_id = ?
            AND o.is_correct = 1
        ");

        $stmt->bind_param(
            "ii",
            $option_id,
            $question_id
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function getResult(
        $attempt_id,
        $student_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                a.*,
                q.title,
                q.total_marks,
                q.pass_mark

            FROM attempts a

            JOIN quizzes q
            ON q.id = a.quiz_id

            WHERE a.id = ?
            AND a.student_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $attempt_id,
            $student_id
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function attemptHistory(
        $student_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                a.*,
                q.title AS quiz_title,
                q.total_marks,
                c.title AS course_title

            FROM attempts a

            JOIN quizzes q
            ON q.id = a.quiz_id

            JOIN courses c
            ON c.id = q.course_id

            WHERE a.student_id = ?

            ORDER BY a.completed_at DESC
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }
}
?>
