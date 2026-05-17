<?php
// models/ReportModel.php
require_once __DIR__ . '/../config/db.php';

class ReportModel {
    public static function getEnrollmentPerSubject() {
        $sql = "SELECT s.name as subject_name, COUNT(e.id) as enrollment_count
                FROM subjects s
                LEFT JOIN courses c ON s.id = c.subject_id
                LEFT JOIN enrollments e ON c.id = e.course_id
                GROUP BY s.id, s.name
                ORDER BY enrollment_count DESC";
        return db_fetch_all($sql);
    }

    public static function getQuizPassRates() {
        $sql = "SELECT q.title, c.title as course_title,
                COUNT(a.id) as total_attempts,
                SUM(CASE WHEN a.score >= q.pass_mark THEN 1 ELSE 0 END) as pass_count
                FROM quizzes q
                JOIN courses c ON q.course_id = c.id
                LEFT JOIN attempts a ON q.id = a.quiz_id
                WHERE q.quiz_type = 'graded'
                GROUP BY q.id, q.title, c.title";
        return db_fetch_all($sql);
    }

    public static function getMostActiveInstructors() {
        $sql = "SELECT u.name, COUNT(c.id) as course_count
                FROM users u
                JOIN courses c ON u.id = c.instructor_id
                WHERE u.role = 'instructor'
                GROUP BY u.id, u.name
                ORDER BY course_count DESC
                LIMIT 10";
        return db_fetch_all($sql);
    }
}
?>
