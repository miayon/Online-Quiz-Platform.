<?php
// views/ta_header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] !== 'ta') {
    echo "Access Denied. Teaching Assistants Only.";
    exit();
}

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function ta_course_tabs($courseId) {
    $tabs = [
        'ta_course_detail.php' => 'Course Details',
        'ta_quizzes.php' => 'Quizzes',
        'ta_questions.php' => 'Question Bank',
        'ta_results.php' => 'Results',
        'ta_announcements.php' => 'Announcements',
        'ta_materials.php' => 'Materials',
        'ta_qa.php' => 'Q&A Board',
        'ta_sessions.php' => 'Doubt Sessions',
        'ta_report.php' => 'Report'
    ];

    echo "<div class='tabs'>";
    foreach ($tabs as $file => $label) {
        $active = basename($_SERVER['PHP_SELF']) == $file ? 'active' : '';
        echo "<a class='$active' href='$file?course_id=" . intval($courseId) . "'>" . h($label) . "</a>";
    }
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Panel - Online Quiz Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --text: #ecf0f1;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary);
            color: var(--text);
            padding: 20px 0;
            flex-shrink: 0;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: var(--text);
            padding: 15px 25px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: var(--secondary);
            border-left: 5px solid var(--accent);
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .stats-grid, .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card, .card, .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .stat-card h3 {
            margin: 0;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary);
            margin: 10px 0;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        th {
            background-color: #fcfcfc;
            color: #333;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        .badge-success { background: #e8f5e9; color: #388e3c; }
        .badge-danger { background: #ffebee; color: #c62828; }
        .badge-warning { background: #fff3e0; color: #ef6c00; }
        .badge-info { background: #e3f2fd; color: #1976d2; }

        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
            display: inline-block;
            margin: 3px 0;
        }

        .btn-primary { background: var(--accent); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 20px;
            align-items: start;
        }

        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .tabs a {
            background: white;
            padding: 10px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .tabs a.active {
            background: var(--accent);
            color: white;
        }

        details {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 14px;
            margin-bottom: 12px;
        }

        summary {
            cursor: pointer;
            font-weight: 600;
        }

        .qa-answer {
            background: #f4fff6;
            border-left: 4px solid var(--success);
            padding: 12px;
            margin: 10px 0;
        }

        .notice {
            background: #fff3e0;
            padding: 10px;
            border-radius: 6px;
            color: #8a4b00;
        }

        .ajax-box {
            margin-top: 15px;
            padding: 12px;
            background: #eef7ff;
            border-radius: 8px;
        }

        @media(max-width: 900px) {
            body { display: block; }
            .sidebar { width: 100%; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>TA Panel</h2>

    <a href="ta_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ta_dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
    <a href="ta_profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ta_profile.php' ? 'active' : ''; ?>">Manage Profile</a>
    <a href="ta_courses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ta_courses.php' ? 'active' : ''; ?>">Assigned Courses</a>

    <a href="../controllers/auth_controller.php?action=logout" style="margin-top: 50px; color: #ff7675;">Logout</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1>
            <?php
            $page = basename($_SERVER['PHP_SELF'], ".php");
            echo ucwords(str_replace("_", " ", $page));
            ?>
        </h1>
        <div>
            Welcome, <strong><?php echo h($_SESSION['name']); ?></strong>
        </div>
    </div>
