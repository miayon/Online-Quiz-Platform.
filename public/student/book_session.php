<?php

session_start();

require_once "../../core/auth.php";
require_once "../../config/db.php";
require_once "../../app/controllers/DoubtSessionController.php";

studentAuth();

$controller = new DoubtSessionController($conn);

$controller->book(studentId());
?>