<?php
// config/migrate.php
require_once __DIR__ . '/db.php';

echo "<h2>Starting Database Migration...</h2>";

// 1. Check users columns
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'department'");
$exists = $result && $result->num_rows > 0;

if (!$exists) {
    $sql = "ALTER TABLE users ADD COLUMN department VARCHAR(100) NULL, ADD COLUMN bio TEXT NULL";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>Migration Success: 'department' and 'bio' columns added to 'users' table!</p>";
    } else {
        echo "<p style='color: red;'>Migration Failed (users columns): " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>Migration Info: 'department' and 'bio' columns already exist in 'users' table.</p>";
}

// 2. Create ta_student_flags table
$conn->query("
    CREATE TABLE IF NOT EXISTS ta_student_flags (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      course_id INT(11) NOT NULL,
      student_id INT(11) NOT NULL,
      attempt_id INT(11) NOT NULL,
      ta_id INT(11) NOT NULL,
      reason TEXT,
      status VARCHAR(50) DEFAULT 'pending_review',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
echo "<p style='color: green;'>Migration Success: 'ta_student_flags' table checked/created!</p>";

// 3. Create ta_doubt_session_status table
$conn->query("
    CREATE TABLE IF NOT EXISTS ta_doubt_session_status (
      doubt_session_id INT(11) PRIMARY KEY,
      status VARCHAR(50) DEFAULT 'scheduled',
      notice TEXT,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
");
echo "<p style='color: green;'>Migration Success: 'ta_doubt_session_status' table checked/created!</p>";

// 4. Ensure ta_at_risk_threshold setting exists
$thresholdCheck = $conn->query("SELECT * FROM platform_settings WHERE setting_key = 'ta_at_risk_threshold'");
if ($thresholdCheck && $thresholdCheck->num_rows === 0) {
    $conn->query("INSERT INTO platform_settings (setting_key, setting_value) VALUES ('ta_at_risk_threshold', '50')");
    echo "<p style='color: green;'>Migration Success: 'ta_at_risk_threshold' default setting inserted!</p>";
}
?>

