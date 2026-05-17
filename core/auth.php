<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function studentAuth() {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
        header("Location: ../login.php");
        exit();
    }
}

function studentId() {
    return $_SESSION["user_id"];
}
?>