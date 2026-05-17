<?php
session_start();
require_once __DIR__ . "/../../config/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $student_id = trim($_POST["student_id"] ?? "");
    $program = trim($_POST["program"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Validation
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $email_exists = $check_email->get_result()->num_rows > 0;

    $check_sid = $conn->prepare("SELECT id FROM users WHERE student_id = ? LIMIT 1");
    $check_sid->bind_param("s", $student_id);
    $check_sid->execute();
    $sid_exists = $check_sid->get_result()->num_rows > 0;

    if ($name === "") $error = "Name is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = "Valid email is required.";
    elseif ($student_id === "") $error = "Student ID is required.";
    elseif ($program === "") $error = "Program is required.";
    elseif (strlen($password) < 6) $error = "Password must be at least 6 characters.";
    elseif ($password !== $confirm_password) $error = "Passwords do not match.";
    elseif ($email_exists) $error = "Email already exists.";
    elseif ($sid_exists) $error = "Student ID already exists.";
    else {
        // Handle optional profile picture upload
        $profile_pic = "";
        if (!empty($_FILES["profile_pic"]["name"])) {
            $allowed = ["jpg", "jpeg", "png", "gif"];
            $ext = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, PNG and GIF images are allowed.";
            } else {
                $profile_pic = "student_" . time() . "_" . rand(1000, 9999) . "." . $ext;
                $target = __DIR__ . "/../../uploads/" . $profile_pic;
                if (!is_dir(__DIR__ . "/../../uploads")) {
                    mkdir(__DIR__ . "/../../uploads", 0777, true);
                }
                move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target);
            }
        }

        if ($error === "") {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $role = "student";
            $is_active = 1;

            $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash, phone, role, profile_pic, student_id, program, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssi", $name, $email, $password_hash, $phone, $role, $profile_pic, $student_id, $program, $is_active);
            if ($stmt->execute()) {
                $success = "Registration successful. You can login now.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="../../assets/css/student.css">
</head>
<body class="login-body">

<div class="login-wrapper register-wrapper">
    <div class="login-card register-card">

        <div class="login-brand">
            <h1>Create Account</h1>
            <p>Register as a student</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
                <br>
                <a href="login.php">Go to login</a>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="register-grid">

                <div>
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Enter full name"
                        required
                    >
                </div>

                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter email"
                        required
                    >
                </div>

                <div>
                    <label>Phone</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="Enter phone number"
                    >
                </div>

                <div>
                    <label>Student ID</label>
                    <input
                        type="text"
                        name="student_id"
                        class="form-control"
                        placeholder="Example: CSE-2024-001"
                        required
                    >
                </div>

                <div>
                    <label>Program</label>
                    <input
                        type="text"
                        name="program"
                        class="form-control"
                        placeholder="Example: BSc in CSE"
                        required
                    >
                </div>

                <div>
                    <label>Profile Picture</label>
                    <input
                        type="file"
                        name="profile_pic"
                        class="form-control"
                    >
                </div>

                <div>
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Minimum 6 characters"
                        required
                    >
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Confirm password"
                        required
                    >
                </div>

            </div>

            <button type="submit" class="btn login-btn">
                Register
            </button>

        </form>

        <p class="login-note">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>
</div>

</body>
</html>