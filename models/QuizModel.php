<?php
// models/QuizModel.php
require_once __DIR__ . '/../config/db.php';

class QuizModel {
    public static function getAllWithDetails($course_id = null, $status = null, $quiz_type = null) {
        $sql = "SELECT q.*, c.title as course_title, u.name as creator_name,
                (SELECT COUNT(*) FROM attempts a WHERE a.quiz_id = q.id) as attempt_count
                FROM quizzes q
                LEFT JOIN courses c ON q.course_id = c.id
                LEFT JOIN users u ON q.created_by = u.id";
        
        $conditions = [];
        $params = [];
        $types = "";

        if ($course_id !== null && $course_id !== '') {
            $conditions[] = "q.course_id = ?";
            $params[] = intval($course_id);
            $types .= "i";
        }

        if ($status !== null && $status !== '') {
            $conditions[] = "q.status = ?";
            $params[] = trim($status);
            $types .= "s";
        }

        if ($quiz_type !== null && $quiz_type !== '') {
            $conditions[] = "q.quiz_type = ?";
            $params[] = trim($quiz_type);
            $types .= "s";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY q.id DESC";
        return db_fetch_all($sql, $params, $types);
    }
}
?>
