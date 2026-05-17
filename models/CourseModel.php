<?php
// models/CourseModel.php
require_once __DIR__ . '/../config/db.php';

class CourseModel {
    public static function getAllWithDetails($subject_id = null, $instructor_id = null) {
        $sql = "SELECT c.*, u.name as instructor_name, s.name as subject_name, 
                (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id) as student_count
                FROM courses c 
                LEFT JOIN users u ON c.instructor_id = u.id 
                LEFT JOIN subjects s ON c.subject_id = s.id";
        
        $conditions = [];
        $params = [];
        $types = "";

        if ($subject_id !== null && $subject_id !== '') {
            $conditions[] = "c.subject_id = ?";
            $params[] = intval($subject_id);
            $types .= "i";
        }

        if ($instructor_id !== null && $instructor_id !== '') {
            $conditions[] = "c.instructor_id = ?";
            $params[] = intval($instructor_id);
            $types .= "i";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY c.created_at DESC";
        return db_fetch_all($sql, $params, $types);
    }
}
?>
