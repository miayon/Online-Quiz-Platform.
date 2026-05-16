<?php
require_once __DIR__ . "/init.php";

$course_id = $_GET["course_id"] ?? 0;

if ($courseModel->enroll($student_id, $course_id)) {
    header("Location: courses.php?message=Enrolled successfully");
} else {
    header("Location: courses.php?error=Enrollment failed");
}
exit();
?>
