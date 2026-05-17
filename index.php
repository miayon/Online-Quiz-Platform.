<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION["role"] === "instructor") {
    header("Location: views/instructor_dashboard.php");
    exit();
}

header("Location: login.php");
exit();
?>
