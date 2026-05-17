<?php
// api/check_email.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo json_encode(['error' => 'Email is required', 'exists' => false]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email format', 'exists' => false]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $exists = $row['count'] > 0;
    
    echo json_encode(['exists' => $exists]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error', 'exists' => false]);
}
exit;
?>
