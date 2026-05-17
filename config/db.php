<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "online_quiz_platform";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

function db_query($sql, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        if ($types === "") {
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i";
                } elseif (is_double($param)) {
                    $types .= "d";
                } else {
                    $types .= "s";
                }
            }
        }
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}


function db_fetch_one($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    return $stmt->get_result()->fetch_assoc();
}

function db_fetch_all($sql, $params = [], $types = "") {
    $stmt = db_query($sql, $params, $types);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function log_action($action, $details = "") {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!empty($_SESSION['user_id'])) {
        db_query(
            "INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)",
            [intval($_SESSION['user_id']), $action, $details],
            "iss"
        );
    }
}

if (!function_exists('h')) {
    function h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
?>