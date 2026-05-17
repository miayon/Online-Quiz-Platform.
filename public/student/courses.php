<?php

session_start();

require_once "../../core/auth.php";

require_once "../../config/db.php";

require_once "../../app/controllers/CourseController.php";

studentAuth();

$controller =
    new CourseController($conn);

$controller->courses(
    studentId()
);
?>