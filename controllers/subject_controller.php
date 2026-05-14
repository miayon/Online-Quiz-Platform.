<?php
// controllers/subject_controller.php
session_start();
require_once __DIR__ . '/../models/SubjectModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        if (!empty($name)) {
            SubjectModel::create($name, $description);
        }
    } elseif ($action === 'update') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        if (!empty($name)) {
            SubjectModel::update($id, $name, $description);
        }
    }
    header("Location: ../views/manage_subjects.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    SubjectModel::delete($id);
    header("Location: ../views/manage_subjects.php");
    exit();
}
?>
