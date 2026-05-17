<?php
// config/migrate.php
require_once __DIR__ . '/db.php';

echo "<h2>Starting Database Migration...</h2>";

// Check if columns exist
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'department'");
$exists = $result && $result->num_rows > 0;

if (!$exists) {
    $sql = "ALTER TABLE users ADD COLUMN department VARCHAR(100) NULL, ADD COLUMN bio TEXT NULL";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>Migration Success: 'department' and 'bio' columns added to 'users' table!</p>";
    } else {
        echo "<p style='color: red;'>Migration Failed: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: blue;'>Migration Info: 'department' and 'bio' columns already exist in 'users' table.</p>";
}
?>
