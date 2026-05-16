<?php
require_once __DIR__ . "/init.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quizzes.php");
    exit();
}

$quiz_id = $_POST["quiz_id"] ?? 0;
$user_answers = $_POST["answers"] ?? [];

$quiz = $quizModel->getQuiz($quiz_id);
if (!$quiz) {
    header("Location: quizzes.php?error=Quiz not found");
    exit();
}

$total_score = 0;

// Calculate score
foreach ($user_answers as $question_id => $option_id) {
    $correct = $quizModel->checkCorrect($question_id, $option_id);
    if ($correct) {
        $total_score += $correct["marks"];
    }
}

// Save attempt
$attempt_id = $quizModel->saveAttempt($quiz_id, $student_id, $total_score);

// Save individual answers
foreach ($user_answers as $question_id => $option_id) {
    $quizModel->saveAnswer($attempt_id, $question_id, $option_id);
}

header("Location: result.php?attempt_id=$attempt_id");
exit();
?>
