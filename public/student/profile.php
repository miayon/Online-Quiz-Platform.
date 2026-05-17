<?php

session_start();

require_once "../../core/auth.php";

require_once "../../config/db.php";

require_once "../../app/controllers/ProfileController.php";

studentAuth();

$controller =
    new ProfileController($conn);

$controller->profile(
    studentId()
);
?>