<?php
// models/SubjectModel.php
require_once __DIR__ . '/../config/db.php';

class SubjectModel {
    public static function getAll() {
        return db_fetch_all("SELECT * FROM subjects ORDER BY name ASC");
    }

    public static function create($name, $description) {
        return db_query("INSERT INTO subjects (name, description) VALUES (?, ?)", [$name, $description]);
    }

    public static function update($id, $name, $description) {
        return db_query("UPDATE subjects SET name = ?, description = ? WHERE id = ?", [$name, $description, $id], "ssi");
    }

    public static function delete($id) {
        return db_query("DELETE FROM subjects WHERE id = ?", [$id], "i");
    }

    public static function getById($id) {
        return db_fetch_one("SELECT * FROM subjects WHERE id = ?", [$id]);
    }
}
?>
