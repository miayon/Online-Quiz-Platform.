<?php
// config/db.php

$host = 'localhost';
$dbname = 'online_quiz_platform';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to safely execute prepared statements
function db_query($sql, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return false;
    }

    if (!empty($params)) {
        if (empty($types)) {
            $types = str_repeat("s", count($params)); // Default to string
        }
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt;
}

function db_fetch_all($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    if ($stmt === false) return [];
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}

function db_fetch_one($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    if ($stmt === false) return null;
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data;
}

function log_action($action, $details = "") {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    db_query("INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)", [$user_id, $action, $details], "iss");
}
?>
