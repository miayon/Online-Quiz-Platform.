<?php

session_start();

require_once "../../core/auth.php";
require_once "../../config/db.php";

require_once "../../app/controllers/QuizController.php";

studentAuth();

$controller =
    new QuizController($conn);

$controller->result(
    studentId()
);
?>