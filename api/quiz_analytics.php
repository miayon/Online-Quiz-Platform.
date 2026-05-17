<?php
// api/quiz_analytics.php
header("Content-Type: application/json");

session_start();

require_once __DIR__ . "/../models/InstructorModel.php";

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "instructor") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$instructorId = (int)$_SESSION["user_id"];
$quizId = (int)($_GET["quiz_id"] ?? 0);

if (!$quizId || !InstructorModel::ownsQuiz($instructorId, $quizId)) {
    echo json_encode(["success" => false, "message" => "Invalid quiz."]);
    exit();
}

$analytics = InstructorModel::getQuizAnalytics($instructorId, $quizId);

echo json_encode([
    "success" => true,
    "analytics" => $analytics
]);
?>
