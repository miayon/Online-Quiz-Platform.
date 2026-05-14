<?php
// models/UserModel.php
require_once __DIR__ . '/../config/db.php';

class UserModel {
    public static function authenticate($email, $password) {
        $user = db_fetch_one("SELECT * FROM users WHERE email = ?", [$email]);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public static function getById($id) {
        return db_fetch_one("SELECT * FROM users WHERE id = ?", [$id]);
    }

    public static function getAll() {
        return db_fetch_all("SELECT * FROM users ORDER BY created_at DESC");
    }

    public static function searchUsers($query) {
        $searchTerm = "%$query%";
        return db_fetch_all("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? OR student_id LIKE ? ORDER BY created_at DESC", 
                            [$searchTerm, $searchTerm, $searchTerm]);
    }

    public static function updateStatus($id, $status) {
        return db_query("UPDATE users SET is_active = ? WHERE id = ?", [$status, $id], "ii");
    }

    public static function changeRole($id, $role) {
        return db_query("UPDATE users SET role = ? WHERE id = ?", [$role, $id], "si");
    }

    public static function delete($id) {
        return db_query("DELETE FROM users WHERE id = ?", [$id], "i");
    }

    public static function getStats() {
        $stats = [];
        $stats['total_users'] = db_fetch_one("SELECT COUNT(*) as count FROM users")['count'];
        $stats['total_students'] = db_fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'student'")['count'];
        $stats['total_instructors'] = db_fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'instructor'")['count'];
        $stats['total_tas'] = db_fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'ta'")['count'];
        $stats['pending_instructors'] = db_fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'instructor' AND is_active = 0")['count'];
        $stats['pending_integrity_flags'] = db_fetch_one("SELECT COUNT(*) as count FROM integrity_reports WHERE status = 'pending'")['count'];
        return $stats;
    }
}
?>
