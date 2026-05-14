<?php
// index.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect based on role
if ($_SESSION['role'] === 'admin') {
    header("Location: views/dashboard.php");
} else {
    // If other roles were implemented, they would go to their dashboards
    echo "Welcome, " . $_SESSION['name'] . ". Your role is: " . $_SESSION['role'];
    echo "<br><a href='controllers/auth_controller.php?action=logout'>Logout</a>";
}
?>
