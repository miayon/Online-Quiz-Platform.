<?php
// controllers/user_controller.php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($action === 'toggle_status') {
        $user = UserModel::getById($id);
        if ($user) {
            $newStatus = $user['is_active'] == 1 ? 0 : 1;
            UserModel::updateStatus($id, $newStatus);
        }
    } elseif ($action === 'delete') {
        UserModel::delete($id);
    }

    header("Location: ../views/manage_users.php");
    exit();
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create_ta') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

        if (!empty($name) && !empty($email) && !empty($_POST['password'])) {
            db_query("INSERT INTO users (name, email, password_hash, role, is_active) VALUES (?, ?, ?, 'ta', 1)", 
                     [$name, $email, $password], "sss");
            header("Location: ../views/manage_users.php?msg=ta_created");
        } else {
            header("Location: ../views/manage_users.php?error=empty_fields");
        }
        exit();
    }
}
?>
