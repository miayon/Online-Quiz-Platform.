<?php
// models/QuizModel.php
require_once __DIR__ . '/../config/db.php';

class QuizModel {
    public static function getAllWithDetails() {
        $sql = "SELECT q.*, c.title as course_title, u.name as creator_name,
                (SELECT COUNT(*) FROM attempts a WHERE a.quiz_id = q.id) as attempt_count
                FROM quizzes q
                LEFT JOIN courses c ON q.course_id = c.id
                LEFT JOIN users u ON q.created_by = u.id
                ORDER BY q.id DESC";
        return db_fetch_all($sql);
    }
}
?>
