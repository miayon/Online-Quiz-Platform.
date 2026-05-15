<?php
session_start();

require_once "../../core/auth.php";
require_once "../../config/db.php";
require_once "../../app/controllers/StudentController.php";

studentAuth();

$controller = new StudentController($conn);
$controller->dashboard(studentId());
?>