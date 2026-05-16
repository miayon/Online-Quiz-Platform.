<?php
header('Content-Type: application/json');
require_once __DIR__ . "/init.php";

$quiz_id = $_GET["quiz_id"] ?? 0;

if (!$quiz_id) {
    echo json_encode(["status" => "error", "message" => "Invalid quiz ID"]);
    exit();
}

$quizInfo = $leaderboardModel->getQuizInfo($quiz_id);

if (!$quizInfo) {
    echo json_encode(["status" => "error", "message" => "Quiz not found"]);
    exit();
}

$rawLeaderboard = $leaderboardModel->getLeaderboard($quiz_id);
$leaderboard = [];
$rank = 1;

while ($row = $rawLeaderboard->fetch_assoc()) {
    $leaderboard[] = [
        "rank" => $rank++,
        "name" => $row["name"],
        "student_id" => $row["student_id"],
        "score" => $row["best_score"]
    ];
}

echo json_encode([
    "status" => "success",
    "quiz" => $quizInfo,
    "leaderboard" => $leaderboard
]);
exit();
?>
