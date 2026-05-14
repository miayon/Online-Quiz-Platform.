<?php
// views/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Simple Role Check
if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied. Admins Only.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quiz Platform</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --text: #ecf0f1;
            --danger: #e74c3c;
            --success: #2ecc71;
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
        .sidebar a:hover, .sidebar a.active {
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
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-align: center;
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
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #fcfcfc;
            color: #333;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .badge-student { background: #e3f2fd; color: #1976d2; }
        .badge-instructor { background: #f3e5f5; color: #7b1fa2; }
        .badge-ta { background: #e8f5e9; color: #388e3c; }
        .badge-admin { background: #fff3e0; color: #f57c00; }
        
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
        .btn-edit { background: var(--accent); color: white; }
        .btn-delete { background: var(--danger); color: white; }
        .btn-approve { background: var(--success); color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
    <a href="manage_users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">Manage Users</a>
    <a href="manage_subjects.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_subjects.php' ? 'active' : ''; ?>">Subjects</a>
    <a href="manage_courses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_courses.php' ? 'active' : ''; ?>">Courses</a>
    <a href="manage_quizzes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_quizzes.php' ? 'active' : ''; ?>">Quizzes</a>
    <a href="manage_announcements.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_announcements.php' ? 'active' : ''; ?>">Platform Announcements</a>
    <a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">Institutional Reports</a>
    <a href="audit_logs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'audit_logs.php' ? 'active' : ''; ?>">Audit Logs</a>
    <a href="../controllers/auth_controller.php?action=logout" style="margin-top: 50px; color: #ff7675;">Logout</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1><?php 
            $page = basename($_SERVER['PHP_SELF'], ".php");
            echo ucwords(str_replace("_", " ", $page));
        ?></h1>
        <div>
            Welcome, <strong><?php echo $_SESSION['name']; ?></strong>
        </div>
    </div>
