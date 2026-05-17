<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Academic Portal Gatekeeper</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #f4f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .gateway-card {
            width: min(550px, 92%);
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.06);
            text-align: center;
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 28px;
        }
        p.subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .portal-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 12px;
            text-decoration: none;
            color: #2c3e50;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            font-weight: bold;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .portal-btn:hover {
            border-color: #3498db;
            background: #f0f9ff;
            transform: translateY(-2px);
        }
        .portal-btn.admin:hover {
            border-color: #2ecc71;
            background: #f0fdf4;
        }
        .portal-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }
        .portal-btn span {
            font-size: 14px;
            font-weight: normal;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
</head>
<body>
<div class="gateway-card">
    <h1>QuizlyX Institutional Portal</h1>
    <p class="subtitle">Select your academic role below to access your isolated workspace:</p>

    <div class="portal-grid">
        <a href="views/student/login.php" class="portal-btn">
            <div class="portal-icon">🧑‍🎓</div>
            Student Portal
            <span>Enrolment & Quizzes</span>
        </a>

        <a href="views/instructor/login.php" class="portal-btn">
            <div class="portal-icon">👨‍🏫</div>
            Instructor Portal
            <span>Course & Quiz Governance</span>
        </a>

        <a href="views/ta/login.php" class="portal-btn">
            <div class="portal-icon">🧑‍💻</div>
            TA Portal
            <span>Practice Quizzes & doubt sessions</span>
        </a>

        <a href="views/admin/login.php" class="portal-btn admin">
            <div class="portal-icon">👑</div>
            Admin Portal
            <span>Institutional Settings</span>
        </a>
    </div>
</div>
</body>
</html>