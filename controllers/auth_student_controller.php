<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/UserStudentModel.php";

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new UserStudentModel($db);
    }

    public function login() {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"] ?? "");
            $password = $_POST["password"] ?? "";

            if ($email === "" || $password === "") {
                $error = "Email and password are required.";
            } else {
                $user = $this->userModel->findByEmail($email);

                if (!$user) {
                    $error = "Email not found.";
                } elseif ($user["is_active"] != 1) {
                    $error = "Your account is inactive.";
                } elseif (!password_verify($password, $user["password_hash"])) {
                    $error = "Wrong password.";
                } elseif ($user["role"] !== "student") {
                    $error = "Only student login is allowed.";
                } else {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];

                    header("Location: student/dashboard.php");
                    exit();
                }
            }
        }

        include __DIR__ . "/../views/auth/login.php";
    }
}
?>
