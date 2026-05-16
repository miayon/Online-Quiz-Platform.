<?php
require_once __DIR__ . "/init.php";

$course_id = $_GET["course_id"] ?? 0;

if ($courseModel->dropCourse($student_id, $course_id)) {
    header("Location: dashboard.php?message=Course dropped");
} else {
    header("Location: dashboard.php?error=Failed to drop course");
}
exit();
?>
