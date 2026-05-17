<?php

class StudentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getDashboardStats($student_id) {
        $stats = [];

        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM enrollments
            WHERE student_id = ?
            AND status = 'active'
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stats["active_courses"] = $stmt->get_result()->fetch_assoc()["total"] ?? 0;

        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM attempts
            WHERE student_id = ?
            AND completed_at IS NOT NULL
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stats["total_attempts"] = $stmt->get_result()->fetch_assoc()["total"] ?? 0;

        $stmt = $this->conn->prepare("
            SELECT ROUND(AVG(score), 2) AS average_score
            FROM attempts
            WHERE student_id = ?
            AND completed_at IS NOT NULL
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stats["average_score"] = $stmt->get_result()->fetch_assoc()["average_score"] ?? 0;

        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM doubt_session_bookings
            WHERE student_id = ?
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $stats["booked_sessions"] = $stmt->get_result()->fetch_assoc()["total"] ?? 0;

        return $stats;
    }

    public function getDashboardCourses($student_id) {
        $stmt = $this->conn->prepare("
            SELECT 
                c.id,
                c.title,
                c.description,
                e.status,
                e.enrolled_at,
                (
                    SELECT q.title
                    FROM quizzes q
                    WHERE q.course_id = c.id
                    AND q.status = 'published'
                    AND (q.available_until IS NULL OR q.available_until >= NOW())
                    ORDER BY q.available_from ASC
                    LIMIT 1
                ) AS next_quiz
            FROM enrollments e
            JOIN courses c ON c.id = e.course_id
            WHERE e.student_id = ?
            ORDER BY e.enrolled_at DESC
        ");

        $stmt->bind_param("i", $student_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}
?>
