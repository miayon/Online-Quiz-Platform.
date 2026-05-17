<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] === 'ta') {
    header("Location: views/ta_dashboard.php");
    exit();
}
echo "Only Teaching Assistant module is available here.";
echo "<br><a href='controllers/auth_controller.php?action=logout'>Logout</a>";
?>