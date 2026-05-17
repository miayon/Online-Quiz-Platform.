<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'student') {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            $error = "Email not found.";
        } elseif (intval($user["is_active"]) !== 1) {
            $error = "Your account is inactive.";
        } elseif (!password_verify($password, $user["password_hash"])) {
            $error = "Wrong password.";
        } elseif ($user["role"] !== "student") {
            $error = "Only student login is allowed on this page.";
        } else {
            $_SESSION["user_id"] = intval($user["id"]);
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>

    <title>Student Login</title>

    <link rel="stylesheet" href="../../assets/css/student.css">

</head>

<body class="login-body">

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-brand">

            <h1>Online Quiz Platform</h1>

            <p>
                Student Portal Login
            </p>

        </div>

        <?php if (!empty($error)): ?>

            <div class="alert alert-error">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>

        <form method="POST">

            <label>Email Address</label>

            <input 
                class="form-control"
                type="email" 
                name="email" 
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input 
                class="form-control"
                type="password" 
                name="password" 
                placeholder="Enter your password"
                required
            >

            <button
                type="submit"
                class="btn login-btn"
            >
                Login
            </button>

        </form>

        <p class="login-note">

            Enter your student account credentials

        </p>

        <p class="login-note">

            Don't have an account?

            <a href="register.php">
                Register here
            </a>

        </p>

    </div>

</div>

</body>
</html>