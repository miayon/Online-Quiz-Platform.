<?php
// controllers/profile_controller.php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $department = isset($_POST['department']) ? trim($_POST['department']) : '';
        $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';

        // Handle profile picture upload
        $profile_pic = null;
        if (!empty($_FILES['profile_pic']['name'])) {
            $image_name = time() . "_" . basename($_FILES['profile_pic']['name']);
            $target = __DIR__ . "/../assets/uploads/" . $image_name;
            
            if (!is_dir(__DIR__ . "/../assets/uploads/")) {
                mkdir(__DIR__ . "/../assets/uploads/", 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                $profile_pic = $image_name;
            }
        }

        if (UserModel::updateProfile($user_id, $name, $phone, $department, $bio, $profile_pic)) {
            $_SESSION['name'] = $name;
            header("Location: ../views/profile.php?msg=updated");
        } else {
            header("Location: ../views/profile.php?error=failed");
        }
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $user = UserModel::getById($user_id);

        if (!password_verify($old_password, $user['password_hash'])) {
            header("Location: ../views/profile.php?error=wrong_old_password");
        } elseif ($new_password !== $confirm_password) {
            header("Location: ../views/profile.php?error=mismatch");
        } elseif (strlen($new_password) < 6) {
            header("Location: ../views/profile.php?error=too_short");
        } else {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            if (UserModel::updatePassword($user_id, $hash)) {
                header("Location: ../views/profile.php?msg=password_changed");
            } else {
                header("Location: ../views/profile.php?error=failed");
            }
        }
        exit();
    }
}
?>
