<?php
// views/instructor_header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../models/InstructorModel.php";

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "instructor") {
    header("Location: ../login.php");
    exit();
}

$currentInstructor = InstructorModel::getInstructor((int)$_SESSION["user_id"]);

if (!$currentInstructor) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

function active_page($file) {
    return basename($_SERVER["PHP_SELF"]) === $file ? "active" : "";
}

function instructor_course_tabs($courseId) {
    $tabs = [
        "instructor_course_detail.php" => "Course Details",
        "instructor_enrollments.php" => "Enrollments",
        "instructor_assign_ta.php" => "Assign TA",
        "instructor_quizzes.php" => "Quizzes",
        "instructor_questions.php" => "Question Bank",
        "instructor_attempts.php" => "Attempts",
        "instructor_analytics.php" => "Analytics",
        "instructor_announcements.php" => "Announcements",
        "instructor_materials.php" => "Materials",
        "instructor_qa.php" => "Q&A Board",
        "instructor_report.php" => "Report"
    ];

    echo "<div class='tabs'>";

    foreach ($tabs as $file => $label) {
        $active = active_page($file);
        echo "<a class='$active' href='$file?course_id=" . (int)$courseId . "'>" . h($label) . "</a>";
    }

    echo "</div>";
}

function require_instructor_course($instructorId, $courseId) {
    if (!InstructorModel::ownsCourse($instructorId, $courseId)) {
        die("Unauthorized course access.");
    }
}

$pageTitle = $pageTitle ?? "Instructor Panel";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= h($pageTitle) ?></title>
    <link rel="stylesheet" href="../assets/css/instructor.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h2>Instructor Panel</h2>

        <a class="<?= active_page('instructor_dashboard.php') ?>" href="instructor_dashboard.php">Dashboard</a>
        <a class="<?= active_page('instructor_profile.php') ?>" href="instructor_profile.php">Profile</a>
        <a class="<?= active_page('instructor_courses.php') ?>" href="instructor_courses.php">Courses</a>

        <form method="POST" action="../controllers/auth_controller.php">
            <input type="hidden" name="action" value="logout">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </aside>

    <main class="content">
        <div class="topbar">
            <h1><?= h($pageTitle) ?></h1>
            <div>Welcome, <strong><?= h($currentInstructor["name"]) ?></strong></div>
        </div>

        <?php if (!empty($_SESSION["flash"])): ?>
            <div class="flash <?= h($_SESSION["flash"]["type"]) ?>">
                <?= h($_SESSION["flash"]["message"]) ?>
            </div>
            <?php unset($_SESSION["flash"]); ?>
        <?php endif; ?>
