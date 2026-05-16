<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QuizlyX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
        .error-msg {
            color: #f44336;
            background-color: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
        .info-box {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 5px solid #2196f3;
            font-size: 13px;
            color: #0d47a1;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>QuizlyX Login</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-msg">
            <?php
                if ($_GET['error'] == 'empty_fields') echo "Please fill in all fields.";
                elseif ($_GET['error'] == 'invalid_credentials') echo "Invalid email or password.";
                elseif ($_GET['error'] == 'inactive_account') echo "Your account is not active yet.";
                else echo "An error occurred. Please try again.";
            ?>
        </div>
    <?php endif; ?>

    <form action="controllers/auth_controller.php" method="POST">
        <input type="hidden" name="action" value="login">
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

</div>

</body>
</html>
