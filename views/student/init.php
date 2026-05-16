<?php
// views/student/init.php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../models/StudentModel.php";
require_once __DIR__ . "/../../models/SubjectModel.php";
require_once __DIR__ . "/../../models/CourseStudentModel.php";
require_once __DIR__ . "/../../models/ProfileModel.php";
require_once __DIR__ . "/../../models/QAModel.php";
require_once __DIR__ . "/../../models/QuizStudentModel.php";
require_once __DIR__ . "/../../models/PerformanceModel.php";
require_once __DIR__ . "/../../models/ResourceModel.php";
require_once __DIR__ . "/../../models/DoubtSessionModel.php";
require_once __DIR__ . "/../../models/LeaderboardModel.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$studentModel = new StudentModel($conn);
$courseModel = new CourseStudentModel($conn);
$profileModel = new ProfileModel($conn);
$qaModel = new QAModel($conn);
$quizModel = new QuizStudentModel($conn);
$performanceModel = new PerformanceModel($conn);
$resourceModel = new ResourceModel($conn);
$doubtModel = new DoubtSessionModel($conn);
$leaderboardModel = new LeaderboardModel($conn);

// Common data needed by multiple pages
$subjects_list = db_fetch_all("SELECT * FROM subjects");
?>
