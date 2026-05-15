<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/LeaderboardModel.php";

class LeaderboardController {

    private $leaderboardModel;

    public function __construct($db) {
        $this->leaderboardModel = new LeaderboardModel($db);
    }

    public function index($student_id) {

        $quizzes = $this->leaderboardModel
            ->getAvailableQuizzes($student_id);

        include __DIR__ . "/../views/student/leaderboard.php";
    }

    public function ajaxLeaderboard() {

        header("Content-Type: application/json");

        $quiz_id = $_GET["quiz_id"] ?? 0;

        if ($quiz_id <= 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid quiz."
            ]);
            exit();
        }

        $quiz = $this->leaderboardModel
            ->getQuizInfo($quiz_id);

        $result = $this->leaderboardModel
            ->getLeaderboard($quiz_id);

        $rows = [];
        $rank = 1;

        while ($row = $result->fetch_assoc()) {
            $rows[] = [
                "rank" => $rank,
                "name" => $row["name"],
                "student_id" => $row["student_id"],
                "score" => $row["best_score"]
            ];

            $rank++;
        }

        echo json_encode([
            "status" => "success",
            "quiz" => $quiz,
            "leaderboard" => $rows
        ]);

        exit();
    }
}
?>
