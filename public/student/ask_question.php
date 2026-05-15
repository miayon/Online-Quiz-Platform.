<?php

session_start();

require_once "../../core/auth.php";
require_once "../../config/db.php";

require_once "../../app/controllers/QAController.php";

studentAuth();

$controller =
    new QAController($conn);

$controller->ask(
    studentId()
);
?>