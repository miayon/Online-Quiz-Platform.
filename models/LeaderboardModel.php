<?php

class LeaderboardModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAvailableQuizzes($student_id) {

        $stmt = $this->conn->prepare("
            SELECT DISTINCT
                q.id,
                q.title,
                c.title AS course_title
            FROM quizzes q
            JOIN courses c ON c.id = q.course_id
            JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
            AND e.status = 'active'
            AND q.status = 'published'
            ORDER BY c.title ASC, q.title ASC
        ");

        $stmt->bind_param("i", $student_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getLeaderboard($quiz_id) {

        $stmt = $this->conn->prepare("
            SELECT
                u.name,
                u.student_id,
                MAX(a.score) AS best_score
            FROM attempts a
            JOIN users u ON u.id = a.student_id
            WHERE a.quiz_id = ?
            AND a.completed_at IS NOT NULL
            GROUP BY a.student_id
            ORDER BY best_score DESC
            LIMIT 10
        ");

        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getQuizInfo($quiz_id) {

        $stmt = $this->conn->prepare("
            SELECT
                q.title,
                q.total_marks,
                c.title AS course_title
            FROM quizzes q
            JOIN courses c ON c.id = q.course_id
            WHERE q.id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
}
?>
