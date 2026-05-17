<?php

class Profile {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getStudent($student_id) {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM users
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $student_id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateProfile(
        $student_id,
        $name,
        $phone,
        $program,
        $profile_pic
    ) {

        $stmt = $this->conn->prepare("
            UPDATE users
            SET
                name = ?,
                phone = ?,
                program = ?,
                profile_pic = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $name,
            $phone,
            $program,
            $profile_pic,
            $student_id
        );

        return $stmt->execute();
    }

    public function updatePassword(
        $student_id,
        $password_hash
    ) {

        $stmt = $this->conn->prepare("
            UPDATE users
            SET password_hash = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "si",
            $password_hash,
            $student_id
        );

        return $stmt->execute();
    }
}
?>