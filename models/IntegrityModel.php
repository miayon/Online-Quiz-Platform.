<?php
// models/IntegrityModel.php
require_once __DIR__ . '/../config/db.php';

class IntegrityModel {
    public static function getAllReports() {
        $sql = "SELECT ir.*, u1.name as reporter_name, u2.name as student_name, q.title as quiz_title
                FROM integrity_reports ir
                LEFT JOIN users u1 ON ir.reported_by = u1.id
                LEFT JOIN users u2 ON ir.student_id = u2.id
                LEFT JOIN quizzes q ON ir.quiz_id = q.id
                ORDER BY ir.created_at DESC";
        return db_fetch_all($sql);
    }

    public static function updateStatus($id, $status) {
        return db_query("UPDATE integrity_reports SET status = ? WHERE id = ?", [$status, $id], "si");
    }

    public static function delete($id) {
        return db_query("DELETE FROM integrity_reports WHERE id = ?", [$id], "i");
    }
}
?>
