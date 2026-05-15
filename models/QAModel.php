<?php

class QAModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getQuestions() {

        $stmt = $this->conn->prepare("
            SELECT
                q.*,
                u.name AS student_name,
                c.title AS course_title,

                (
                    SELECT COUNT(*)
                    FROM qa_answers a
                    WHERE a.qa_question_id = q.id
                ) AS total_answers

            FROM qa_questions q

            JOIN users u
            ON u.id = q.student_id

            JOIN courses c
            ON c.id = q.course_id

            ORDER BY q.created_at DESC
        ");

        $stmt->execute();

        return $stmt->get_result();
    }

    public function getCourses(
        $student_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                c.id,
                c.title

            FROM enrollments e

            JOIN courses c
            ON c.id = e.course_id

            WHERE e.student_id = ?
            AND e.status = 'active'

            ORDER BY c.title ASC
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function askQuestion(
        $course_id,
        $student_id,
        $title,
        $body
    ) {

        $stmt = $this->conn->prepare("
            INSERT INTO qa_questions
            (
                course_id,
                student_id,
                title,
                body
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiss",
            $course_id,
            $student_id,
            $title,
            $body
        );

        return $stmt->execute();
    }

    public function questionDetails(
        $question_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                q.*,
                u.name AS student_name,
                c.title AS course_title

            FROM qa_questions q

            JOIN users u
            ON u.id = q.student_id

            JOIN courses c
            ON c.id = q.course_id

            WHERE q.id = ?
            LIMIT 1
        ");

        $stmt->bind_param(
            "i",
            $question_id
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function getAnswers(
        $question_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                a.*,
                u.name AS author_name,
                u.role

            FROM qa_answers a

            JOIN users u
            ON u.id = a.author_id

            WHERE a.qa_question_id = ?

            ORDER BY
                a.is_endorsed DESC,
                a.created_at ASC
        ");

        $stmt->bind_param(
            "i",
            $question_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function addAnswer(
        $question_id,
        $author_id,
        $body
    ) {

        $stmt = $this->conn->prepare("
            INSERT INTO qa_answers
            (
                qa_question_id,
                author_id,
                body
            )
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iis",
            $question_id,
            $author_id,
            $body
        );

        return $stmt->execute();
    }

    public function resolveQuestion(
        $question_id
    ) {

        $stmt = $this->conn->prepare("
            UPDATE qa_questions
            SET is_resolved = 1
            WHERE id = ?
        ");

        $stmt->bind_param(
            "i",
            $question_id
        );

        return $stmt->execute();
    }
}
?>
