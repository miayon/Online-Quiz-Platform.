<?php
// models/ReportModel.php
require_once __DIR__ . '/../config/db.php';

class ReportModel {
    public static function getEnrollmentPerSubject($startDate = null, $endDate = null) {
        $sql = "SELECT s.name as subject_name, COUNT(e.id) as enrollment_count
                FROM subjects s
                LEFT JOIN courses c ON s.id = c.subject_id
                LEFT JOIN enrollments e ON c.id = e.course_id";
        
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " WHERE e.enrolled_at BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }

        $sql .= " GROUP BY s.id, s.name ORDER BY enrollment_count DESC";
        return db_fetch_all($sql, $params);
    }

    public static function getQuizPassRates($startDate = null, $endDate = null) {
        $sql = "SELECT q.title, c.title as course_title,
                COUNT(a.id) as total_attempts,
                SUM(CASE WHEN a.score >= q.pass_mark THEN 1 ELSE 0 END) as pass_count
                FROM quizzes q
                JOIN courses c ON q.course_id = c.id
                LEFT JOIN attempts a ON q.id = a.quiz_id
                WHERE q.quiz_type = 'graded'";
        
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " AND a.started_at BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }

        $sql .= " GROUP BY q.id, q.title, c.title";
        return db_fetch_all($sql, $params);
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

    public static function getStudentSummary($student_id) {
        $sql = "SELECT u.name, u.student_id, u.program, 
                       COUNT(DISTINCT e.course_id) as enrolled_courses,
                       COUNT(a.id) as total_attempts,
                       AVG(a.score) as avg_score
                FROM users u
                LEFT JOIN enrollments e ON u.id = e.student_id
                LEFT JOIN attempts a ON u.id = a.student_id
                WHERE u.id = ? OR u.student_id = ?
                GROUP BY u.id";
        return db_fetch_one($sql, [$student_id, $student_id]);
    }

    public static function getStudentAttempts($student_id) {
        $sql = "SELECT a.*, q.title as quiz_title, c.title as course_title
                FROM attempts a
                JOIN quizzes q ON a.quiz_id = q.id
                JOIN courses c ON q.course_id = c.id
                WHERE a.student_id = ? OR (SELECT id FROM users WHERE student_id = ?) = a.student_id
                ORDER BY a.started_at DESC";
        return db_fetch_all($sql, [$student_id, $student_id]);
    }
}
?>
