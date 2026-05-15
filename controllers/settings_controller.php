<?php
// controllers/settings_controller.php
session_start();
require_once __DIR__ . '/../models/SettingsModel.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if (isset($_POST['action']) && $_POST['action'] === 'update_settings') {
    foreach ($_POST['settings'] as $key => $value) {
        SettingsModel::update($key, trim($value));
    }
    log_action("Updated Platform Policies", "Bulk settings update");
    header("Location: ../views/platform_settings.php?msg=updated");
    exit();
}
?>
