<?php

session_start();

require_once "../../core/auth.php";
require_once "../../config/db.php";
require_once "../../app/controllers/ResourceController.php";

studentAuth();

$controller = new ResourceController($conn);

$controller->announcements(studentId());
?>