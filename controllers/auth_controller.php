<?php
// controllers/auth_controller.php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            header("Location: ../login.php?error=empty_fields");
            exit();
        }

        $user = UserModel::authenticate($email, $password);

        if ($user) {
            if ($user['is_active'] == 0 && $user['role'] !== 'admin') {
                header("Location: ../login.php?error=inactive_account");
                exit();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../views/dashboard.php");
            } elseif ($user['role'] === 'student') {
                header("Location: ../views/student/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            header("Location: ../login.php?error=invalid_credentials");
            exit();
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
