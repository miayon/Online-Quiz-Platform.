<?php

session_start();

require_once "../../core/auth.php";

require_once "../../config/db.php";

require_once "../../app/controllers/PerformanceController.php";

studentAuth();

$controller =
    new PerformanceController($conn);

$controller->dashboard(
    studentId()
);
?>