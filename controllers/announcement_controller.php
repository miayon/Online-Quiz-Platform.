<?php
// controllers/announcement_controller.php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create') {
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $author_id = $_SESSION['user_id'];

        if (!empty($title) && !empty($body)) {
            // course_id is NULL for platform-wide announcements
            db_query("INSERT INTO announcements (course_id, author_id, title, body) VALUES (NULL, ?, ?, ?)", 
                     [$author_id, $title, $body], "iss");
            log_action("Created Platform Announcement", "Title: $title");
        }
    }
    header("Location: ../views/manage_announcements.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    db_query("DELETE FROM announcements WHERE id = ?", [$id], "i");
    log_action("Deleted Announcement", "ID: $id");
    header("Location: ../views/manage_announcements.php");
    exit();
}
?>
