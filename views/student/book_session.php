<?php
require_once __DIR__ . "/init.php";

$session_id = $_GET["session_id"] ?? 0;

$result = $doubtModel->bookSession($student_id, $session_id);

if ($result["status"]) {
    header("Location: doubt_sessions.php?message=" . urlencode($result["message"]));
} else {
    header("Location: doubt_sessions.php?error=" . urlencode($result["message"]));
}
exit();
?>
