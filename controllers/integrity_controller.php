<?php
// controllers/integrity_controller.php
session_start();
require_once __DIR__ . '/../models/IntegrityModel.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action === 'resolve') {
        IntegrityModel::updateStatus($id, 'resolved');
        log_action("Resolved Integrity Flag", "Report ID: $id");
    } elseif ($action === 'escalate') {
        IntegrityModel::updateStatus($id, 'escalated');
        log_action("Escalated Integrity Flag", "Report ID: $id");
    } elseif ($action === 'delete') {
        IntegrityModel::delete($id);
        log_action("Deleted Integrity Flag", "Report ID: $id");
    }

    header("Location: ../views/manage_integrity_flags.php");
    exit();
}
?>
