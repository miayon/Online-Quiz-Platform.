<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../models/Register.php";

class RegisterController {

    private $registerModel;

    public function __construct($db) {
        $this->registerModel = new Register($db);
    }

    public function register() {

        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $name = trim($_POST["name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $student_id = trim($_POST["student_id"] ?? "");
            $program = trim($_POST["program"] ?? "");
            $password = $_POST["password"] ?? "";
            $confirm_password = $_POST["confirm_password"] ?? "";

            $profile_pic = "";

            if ($name === "") {
                $error = "Name is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Valid email is required.";
            } elseif ($student_id === "") {
                $error = "Student ID is required.";
            } elseif ($program === "") {
                $error = "Program is required.";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters.";
            } elseif ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } elseif ($this->registerModel->emailExists($email)) {
                $error = "Email already exists.";
            } elseif ($this->registerModel->studentIdExists($student_id)) {
                $error = "Student ID already exists.";
            } else {

                if (!empty($_FILES["profile_pic"]["name"])) {

                    $allowed = ["jpg", "jpeg", "png", "gif"];
                    $ext = strtolower(
                        pathinfo(
                            $_FILES["profile_pic"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );

                    if (!in_array($ext, $allowed)) {
                        $error = "Only JPG, PNG and GIF images are allowed.";
                    } else {

                        if (!is_dir(__DIR__ . "/../../public/uploads")) {
                            mkdir(__DIR__ . "/../../public/uploads", 0777, true);
                        }

                        $profile_pic =
                            "student_" .
                            time() .
                            "_" .
                            rand(1000, 9999) .
                            "." .
                            $ext;

                        $target =
                            __DIR__ .
                            "/../../public/uploads/" .
                            $profile_pic;

                        move_uploaded_file(
                            $_FILES["profile_pic"]["tmp_name"],
                            $target
                        );
                    }
                }

                if ($error === "") {

                    $password_hash =
                        password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        );

                    $created =
                        $this->registerModel
                            ->createStudent(
                                $name,
                                $email,
                                $password_hash,
                                $phone,
                                $student_id,
                                $program,
                                $profile_pic
                            );

                    if ($created) {
                        $success =
                            "Registration successful. You can login now.";
                    } else {
                        $error =
                            "Registration failed. Please try again.";
                    }
                }
            }
        }

        include __DIR__ . "/../views/auth/register.php";
    }
}
?>