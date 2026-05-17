<?php

class Register {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExists($email) {

        $stmt = $this->conn->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function studentIdExists($student_id) {

        $stmt = $this->conn->prepare("
            SELECT id
            FROM users
            WHERE student_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $student_id);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function createStudent(
        $name,
        $email,
        $password_hash,
        $phone,
        $student_id,
        $program,
        $profile_pic
    ) {

        $role = "student";

        /*
            is_active = 1 dile registration korar sathe sathe login korte parbe.
            is_active = 0 dile admin approval lagbe.
        */
        $is_active = 1;

        $stmt = $this->conn->prepare("
            INSERT INTO users
            (
                name,
                email,
                password_hash,
                phone,
                role,
                profile_pic,
                student_id,
                program,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssi",
            $name,
            $email,
            $password_hash,
            $phone,
            $role,
            $profile_pic,
            $student_id,
            $program,
            $is_active
        );

        return $stmt->execute();
    }
}
?>