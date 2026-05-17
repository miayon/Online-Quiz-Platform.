<?php
// models/CourseModel.php
require_once __DIR__ . '/../config/db.php';

class CourseModel {
    public static function getAllWithDetails() {
        $sql = "SELECT c.*, u.name as instructor_name, s.name as subject_name, 
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) as student_count
                FROM courses c 
                LEFT JOIN users u ON c.instructor_id = u.id 
                LEFT JOIN subjects s ON c.subject_id = s.id 
                ORDER BY c.created_at DESC";
        return db_fetch_all($sql);
    }
}
?>
