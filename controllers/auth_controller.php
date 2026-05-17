<?php
// controllers/auth_controller.php
session_start();

require_once __DIR__ . "/../config/db.php";

$action = $_POST["action"] ?? $_GET["action"] ?? "";

if ($action === "login") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $_SESSION["error"] = "Email and password are required.";
        header("Location: ../login.php");
        exit();
    }

    $user = db_fetch_one("SELECT * FROM users WHERE email = ? LIMIT 1", [$email], "s");

    if (!$user || !password_verify($password, $user["password_hash"])) {
        $_SESSION["error"] = "Invalid email or password.";
        header("Location: ../login.php");
        exit();
    }

    if ($user["role"] !== "instructor") {
        $_SESSION["error"] = "Only Instructor can login here.";
        header("Location: ../login.php");
        exit();
    }

    if ((int)$user["is_active"] !== 1) {
        $_SESSION["error"] = "Your account is inactive.";
        header("Location: ../login.php");
        exit();
    }

    $_SESSION["user_id"] = (int)$user["id"];
    $_SESSION["name"] = $user["name"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    header("Location: ../views/instructor_dashboard.php");
    exit();
}

if ($action === "logout") {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

header("Location: ../login.php");
exit();
?>
