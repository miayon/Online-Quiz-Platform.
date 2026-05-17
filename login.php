<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (isset($_SESSION["user_id"]) && ($_SESSION["role"] ?? "") === "instructor") {
    header("Location: views/instructor_dashboard.php");
    exit();
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Instructor Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <h1>Instructor Login</h1>
        <p>Online Quiz & Exam Platform</p>

        <?php if ($error): ?>
            <div class="flash danger"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="controllers/auth_controller.php">
            <input type="hidden" name="action" value="login">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="instructor@quiz.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="password" required>
            </div>

            <button class="btn" style="width:100%;" type="submit">Login</button>
        </form>

        <div class="demo-box">
            <strong>Demo Login</strong><br>
            Email: instructor@quiz.com<br>
            Password: password
        </div>
    </div>
</body>
</html>
