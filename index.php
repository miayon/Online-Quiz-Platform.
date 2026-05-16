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
} elseif ($_SESSION['role'] === 'student') {
    header("Location: views/student/dashboard.php");
} else {
    echo "Welcome, " . $_SESSION['name'] . ". Your role is: " . $_SESSION['role'];
    echo "<br><a href='controllers/auth_controller.php?action=logout'>Logout</a>";
}
?>
