<?php
session_start();
$action = $_GET['action'] ?? '';
if ($action === 'logout') {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
header("Location: ../login.php");
exit();
?>