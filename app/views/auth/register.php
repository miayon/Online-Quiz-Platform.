<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
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