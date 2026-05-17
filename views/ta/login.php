<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'ta') {
    header("Location: ../ta_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Email and password are required.";
    } else {
        $user = db_fetch_one(
            "SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1",
            [$email],
            "s"
        );

        if (!$user) {
            $error = "User not found.";
        } elseif (!password_verify($password, $user["password_hash"])) {
            $error = "Wrong password.";
        } elseif (intval($user["is_active"]) !== 1) {
            $error = "Account is not active.";
        } elseif ($user["role"] !== "ta") {
            $error = "This page is only for Teaching Assistant login.";
        } else {
            $_SESSION["user_id"] = intval($user["id"]);
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            header("Location: ../ta_dashboard.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TA Login</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#f4f7fb;font-family:Segoe UI,Tahoma,sans-serif}
        .card{width:min(430px,92%);background:white;padding:30px;border-radius:12px;box-shadow:0 10px 35px rgba(0,0,0,.08)}
        h1{text-align:center;color:#2c3e50;margin-top:0}
        p{text-align:center;color:#666}
        label{display:block;font-weight:600;margin-bottom:7px}
        input{width:100%;padding:11px;border:1px solid #ddd;border-radius:6px;box-sizing:border-box;margin-bottom:16px}
        button{width:100%;padding:12px;background:#3498db;color:white;border:0;border-radius:6px;font-weight:bold;cursor:pointer}
        .error{padding:12px;background:#ffebee;color:#c62828;border-radius:6px;margin-bottom:15px}
        .demo{margin-top:18px;background:#eef7ff;padding:12px;border-radius:6px;font-size:14px}
    </style>
</head>
<body>
<div class="card">
    <h1>Teaching Assistant Login</h1>
    <p>Online Quiz Platform</p>
    <?php if ($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" value="ta@quiz.com" required>
        <label>Password</label>
        <input type="password" name="password" value="password" required>
        <button type="submit">Login</button>
    </form>
    <div class="demo"><b>Demo Login</b><br>Email: ta@quiz.com<br>Password: password</div>
</div>
</body>
</html>
