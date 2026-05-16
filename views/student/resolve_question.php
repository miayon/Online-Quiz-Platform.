<?php
require_once __DIR__ . "/init.php";

$question_id = $_GET["id"] ?? 0;

if ($qaModel->resolveQuestion($question_id)) {
    header("Location: answer_view.php?id=$question_id&message=Question resolved");
} else {
    header("Location: answer_view.php?id=$question_id&error=Action failed");
}
exit();
?>
