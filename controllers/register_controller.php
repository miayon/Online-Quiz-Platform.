<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/RegisterModel.php";

class RegisterController {

    private $registerModel;

    public function __construct($db) {
        $this->registerModel = new RegisterModel($db);
    }

    public function register() {

        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $role = trim($_POST["role"] ?? "student");
            $name = trim($_POST["name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $student_id = trim($_POST["student_id"] ?? "");
            $program = trim($_POST["program"] ?? "");
            $department = trim($_POST["department"] ?? "");
            $bio = trim($_POST["bio"] ?? "");
            $password = $_POST["password"] ?? "";
            $confirm_password = $_POST["confirm_password"] ?? "";

            $profile_pic = "";

            if ($name === "") {
                $error = "Name is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Valid email is required.";
            } elseif ($role === "student" && $student_id === "") {
                $error = "Student ID is required.";
            } elseif ($role === "student" && $program === "") {
                $error = "Program is required.";
            } elseif ($role === "instructor" && $department === "") {
                $error = "Department is required.";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters.";
            } elseif ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } elseif ($this->registerModel->emailExists($email)) {
                $error = "Email already exists.";
            } elseif ($role === "student" && $this->registerModel->studentIdExists($student_id)) {
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

                        if (!is_dir(__DIR__ . "/../assets/uploads")) {
                            mkdir(__DIR__ . "/../assets/uploads", 0777, true);
                        }

                        $profile_pic =
                            $role . "_" .
                            time() .
                            "_" .
                            rand(1000, 9999) .
                            "." .
                            $ext;

                        $target =
                            __DIR__ .
                            "/../assets/uploads/" .
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

                    if ($role === "student") {
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
                    } else {
                        $created =
                            $this->registerModel
                                ->createInstructor(
                                    $name,
                                    $email,
                                    $password_hash,
                                    $phone,
                                    $department,
                                    $bio,
                                    $profile_pic
                                );
                    }

                    if ($created) {
                        if ($role === "student") {
                            $success = "Registration successful. You can login now.";
                        } else {
                            $success = "Registration successful! Your instructor request is pending administrative approval.";
                        }
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                }
            }
        }

        include __DIR__ . "/../views/auth/register.php";
    }
}
// Auto-execute if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == 'register_controller.php') {
    $controller = new RegisterController($conn);
    $controller->register();
}
?>
