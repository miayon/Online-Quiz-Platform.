<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - QuizlyX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text: #f8fafc;
            --text-dim: #94a3b8;
            --accent: #818cf8;
            --error: #ef4444;
            --success: #22c55e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent),
                              radial-gradient(circle at bottom left, rgba(129, 140, 248, 0.1), transparent);
        }

        .register-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .register-card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h1 {
            margin: 0;
            font-size: 32px;
            color: var(--accent);
            letter-spacing: -1px;
        }

        .brand p {
            color: var(--text-dim);
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dim);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: rgba(15, 23, 42, 0.8);
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .footer-note {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-dim);
        }

        .footer-note a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-card">
        <div class="brand">
            <h1>QuizlyX</h1>
            <p>Join our learning community</p>
        </div>

        <?php if (isset($error) && !empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success) && !empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
                <br>
                <a href="login.php" style="color: inherit; font-weight: bold;">Login now</a>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="grid-2">
                <div class="form-group" style="grid-column: span 2;">
                    <label for="role">Register As</label>
                    <select name="role" id="role" style="width: 100%; padding: 12px 16px; background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; color: white; font-size: 15px;" onchange="toggleRoleFields()">
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" placeholder="+880 1xxx xxxxxx">
                </div>
                <div class="form-group student-only">
                    <label for="student_id">Student ID</label>
                    <input type="text" name="student_id" id="student_id" required placeholder="CSE-2024-001">
                </div>
                <div class="form-group student-only">
                    <label for="program">Academic Program</label>
                    <input type="text" name="program" id="program" required placeholder="BSc in CSE">
                </div>
                <div class="form-group instructor-only" style="display: none;">
                    <label for="department">Department</label>
                    <input type="text" name="department" id="department" placeholder="e.g. Computer Science">
                </div>
                <div class="form-group instructor-only" style="display: none; grid-column: span 2;">
                    <label for="bio">Biography / Research Areas</label>
                    <input type="text" name="bio" id="bio" placeholder="Brief details about your academic background...">
                </div>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="padding: 8px;">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="footer-note">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</div>

<script>
function toggleRoleFields() {
    var role = document.getElementById('role').value;
    var studentFields = document.querySelectorAll('.student-only');
    var instructorFields = document.querySelectorAll('.instructor-only');
    
    var studentIdInput = document.getElementById('student_id');
    var programInput = document.getElementById('program');
    var departmentInput = document.getElementById('department');

    if (role === 'student') {
        studentFields.forEach(el => el.style.display = 'block');
        instructorFields.forEach(el => el.style.display = 'none');
        
        studentIdInput.setAttribute('required', 'required');
        programInput.setAttribute('required', 'required');
        departmentInput.removeAttribute('required');
    } else {
        studentFields.forEach(el => el.style.display = 'none');
        instructorFields.forEach(el => el.style.display = 'block');
        
        studentIdInput.removeAttribute('required');
        programInput.removeAttribute('required');
        departmentInput.setAttribute('required', 'required');
    }
}
// Run on load
document.addEventListener('DOMContentLoaded', toggleRoleFields);
</script>

</body>
</html>
