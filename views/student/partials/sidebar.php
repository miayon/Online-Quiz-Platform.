<aside class="sidebar">
    <div class="brand">
        <h2 style="margin: 0; color: #fff; font-size: 24px; letter-spacing: 1px;">QuizlyX</h2>
        <small style="color: #fff; opacity: 0.6; text-transform: uppercase; font-size: 10px; letter-spacing: 2px;">Student Portal</small>
    </div>

    <nav class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="courses.php">Courses</a>
        <a href="quizzes.php">Quizzes</a>
        <a href="attempt_history.php">Attempt History</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="performance.php">Performance</a>
        <a href="qa_board.php">Q&A Board</a>
        <a href="materials.php">Materials</a>
        <a href="announcements.php">Announcements</a>
        <a href="doubt_sessions.php">Doubt Sessions</a>
        <a href="../../controllers/auth_controller.php?action=logout" class="logout">Logout</a>
    </nav>
</aside>

<main class="main-content">
    <div class="topbar">
        <div>
            <h3>Welcome, <?= htmlspecialchars($_SESSION["name"] ?? "Student") ?></h3>
            <p>Manage your courses, quizzes and progress</p>
        </div>
    </div>