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
            // Screen for active/inactive accounts (except Admins)
            if (intval($user['is_active']) !== 1 && $user['role'] !== 'admin') {
                header("Location: ../login.php?error=inactive_account");
                exit();
            }

            $_SESSION['user_id'] = intval($user['id']);
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../views/dashboard.php");
                        } elseif ($user['role'] === 'student') {
                header("Location: ../views/student/dashboard.php");
            } elseif ($user['role'] === 'ta') {
                header("Location: ../views/ta_dashboard.php");
            } elseif ($user['role'] === 'instructor') {
                header("Location: ../views/instructor_dashboard.php");
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

if ((isset($_GET['action']) && $_GET['action'] === 'logout') || (isset($_POST['action']) && $_POST['action'] === 'logout')) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>