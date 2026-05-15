<?php
session_start();

require_once "../../../core/auth.php";
require_once "../../../config/db.php";
require_once "../../../app/controllers/LeaderboardController.php";

studentAuth();

$controller = new LeaderboardController($conn);
$controller->ajaxLeaderboard();
?>
