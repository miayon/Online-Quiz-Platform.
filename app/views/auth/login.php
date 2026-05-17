<!DOCTYPE html>
<html>

<head>

    <title>Student Login</title>

    <link rel="stylesheet" href="assets/css/style.css">

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