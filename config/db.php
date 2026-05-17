<?php
// config/db.php
// XAMPP default connection. This file creates the shared database tables and safely seeds demo data.
// This repaired version avoids duplicate email errors if TA/Student data already exists.

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "online_quiz_platform";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
    $conn->set_charset("utf8mb4");
    $conn->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $conn->select_db($DB_NAME);
} catch (Throwable $e) {
    die("Database connection failed. Start MySQL in XAMPP. Error: " . htmlspecialchars($e->getMessage()));
}

function db_query($sql, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
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

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function column_exists($table, $column) {
    global $conn;
    $safeTable = $conn->real_escape_string($table);
    $safeColumn = $conn->real_escape_string($column);
    $result = $conn->query("SHOW COLUMNS FROM `$safeTable` LIKE '$safeColumn'");
    return $result && $result->num_rows > 0;
}

function ensure_column($table, $column, $definition) {
    global $conn;
    if (!column_exists($table, $column)) {
        $conn->query("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

function log_action($action, $details = "") {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION["user_id"])) {
        db_query(
            "INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)",
            [(int)$_SESSION["user_id"], $action, $details],
            "iss"
        );
    }
}

function seed_user($name, $email, $hash, $phone, $role, $studentId, $program, $bio = "") {
    $existing = db_fetch_one("SELECT id FROM users WHERE email = ? LIMIT 1", [$email], "s");

    if ($existing) {
        // Keep existing account but make sure it is active and has the expected role.
        db_query(
            "UPDATE users SET name = ?, phone = ?, role = ?, student_id = ?, program = ?, bio = ?, is_active = 1 WHERE email = ?",
            [$name, $phone, $role, $studentId, $program, $bio, $email],
            "sssssss"
        );
        return (int)$existing["id"];
    }

    db_query(
        "INSERT INTO users (name, email, password_hash, phone, role, student_id, program, bio, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)",
        [$name, $email, $hash, $phone, $role, $studentId, $program, $bio],
        "ssssssss"
    );

    global $conn;
    return (int)$conn->insert_id;
}

function seed_subject($name, $description) {
    $existing = db_fetch_one("SELECT id FROM subjects WHERE name = ? LIMIT 1", [$name], "s");

    if ($existing) {
        return (int)$existing["id"];
    }

    db_query("INSERT INTO subjects (name, description) VALUES (?, ?)", [$name, $description], "ss");

    global $conn;
    return (int)$conn->insert_id;
}

function seed_course($instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status) {
    $existing = db_fetch_one(
        "SELECT id FROM courses WHERE instructor_id = ? AND title = ? LIMIT 1",
        [$instructorId, $title],
        "is"
    );

    if ($existing) {
        return (int)$existing["id"];
    }

    db_query(
        "INSERT INTO courses (instructor_id, subject_id, title, description, enrollment_type, max_students, status)
         VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status],
        "iisssis"
    );

    global $conn;
    return (int)$conn->insert_id;
}

function seed_quiz($courseId, $createdBy, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status) {
    $existing = db_fetch_one(
        "SELECT id FROM quizzes WHERE course_id = ? AND title = ? LIMIT 1",
        [$courseId, $title],
        "is"
    );

    if ($existing) {
        return (int)$existing["id"];
    }

    db_query(
        "INSERT INTO quizzes (course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))",
        [$courseId, $createdBy, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status],
        "iissiiiss"
    );

    global $conn;
    return (int)$conn->insert_id;
}

function seed_question($quizId, $questionText, $marks, $orderIndex, $createdBy, $options) {
    $existing = db_fetch_one(
        "SELECT id FROM questions WHERE quiz_id = ? AND question_text = ? LIMIT 1",
        [$quizId, $questionText],
        "is"
    );

    if ($existing) {
        return (int)$existing["id"];
    }

    db_query(
        "INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by) VALUES (?, ?, ?, ?, ?)",
        [$quizId, $questionText, $marks, $orderIndex, $createdBy],
        "isiii"
    );

    global $conn;
    $questionId = (int)$conn->insert_id;

    foreach ($options as $opt) {
        db_query(
            "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)",
            [$questionId, $opt[0], $opt[1]],
            "isi"
        );
    }

    return $questionId;
}

function initialize_database() {
    global $conn;

    $schema = [];

    $schema[] = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password_hash VARCHAR(255),
        phone VARCHAR(20),
        role VARCHAR(20) DEFAULT 'student',
        profile_pic VARCHAR(255),
        student_id VARCHAR(50),
        program VARCHAR(100),
        bio TEXT,
        is_active TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        description TEXT
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        instructor_id INT,
        subject_id INT,
        title VARCHAR(150),
        description TEXT,
        enrollment_type VARCHAR(20) DEFAULT 'open',
        max_students INT DEFAULT 50,
        status VARCHAR(20) DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS course_tas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        ta_id INT,
        assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        course_id INT,
        status VARCHAR(20) DEFAULT 'pending',
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS quizzes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        created_by INT,
        title VARCHAR(150),
        description TEXT,
        time_limit_minutes INT,
        total_marks INT,
        pass_mark INT,
        quiz_type VARCHAR(50) DEFAULT 'graded',
        status VARCHAR(50) DEFAULT 'draft',
        available_from DATETIME,
        available_until DATETIME
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT,
        question_text TEXT,
        marks INT,
        order_index INT,
        created_by INT
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS options (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT,
        option_text VARCHAR(255),
        is_correct INT DEFAULT 0
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT,
        student_id INT,
        score INT DEFAULT 0,
        started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        completed_at TIMESTAMP NULL,
        is_graded INT DEFAULT 1
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS answers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        attempt_id INT,
        question_id INT,
        selected_option_id INT
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS course_materials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        uploaded_by INT,
        title VARCHAR(150),
        file_path VARCHAR(255),
        material_type VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        author_id INT,
        title VARCHAR(150),
        body TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS qa_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        student_id INT,
        title VARCHAR(150),
        body TEXT,
        is_resolved INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS qa_answers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        qa_question_id INT,
        author_id INT,
        body TEXT,
        is_endorsed INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS doubt_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT,
        ta_id INT,
        title VARCHAR(150),
        scheduled_at DATETIME,
        duration_minutes INT,
        location_or_link VARCHAR(255),
        max_attendees INT DEFAULT 10
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS doubt_session_bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        doubt_session_id INT,
        student_id INT,
        booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        action VARCHAR(255),
        details TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS integrity_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reported_by INT,
        quiz_id INT,
        student_id INT,
        reason TEXT,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $schema[] = "CREATE TABLE IF NOT EXISTS platform_settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value VARCHAR(255)
    )";

    foreach ($schema as $sql) {
        $conn->query($sql);
    }

    ensure_column("users", "bio", "TEXT");

    db_query(
        "INSERT INTO platform_settings (setting_key, setting_value)
         VALUES ('max_quiz_duration', '120')
         ON DUPLICATE KEY UPDATE setting_value = setting_value"
    );

    // Password for every demo user: password
    $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    $instructorId = seed_user(
        "John Instructor",
        "instructor@quiz.com",
        $hash,
        "+8801000000001",
        "instructor",
        null,
        "Computer Science",
        "Senior instructor for programming and data structures."
    );

    $taId = seed_user(
        "Teaching Assistant",
        "ta@quiz.com",
        $hash,
        "+8801000000002",
        "ta",
        null,
        "Computer Science",
        "Assigned teaching assistant."
    );

    $s1 = seed_user("Ayesha Khan", "ayesha@student.com", $hash, "+8801111111111", "student", "S-101", "CSE");
    $s2 = seed_user("Nafis Ahmed", "nafis@student.com", $hash, "+8801222222222", "student", "S-102", "CSE");
    $s3 = seed_user("Tania Islam", "tania@student.com", $hash, "+8801333333333", "student", "S-103", "CSE");

    $subjectId = seed_subject("Computer Science", "CSE related subjects");
    seed_subject("Mathematics", "Mathematics related subjects");

    $courseId = seed_course(
        $instructorId,
        $subjectId,
        "Data Structures",
        "Stacks, queues, trees, graphs, and algorithmic thinking.",
        "open",
        60,
        "active"
    );

    if (!db_fetch_one("SELECT id FROM course_tas WHERE course_id = ? AND ta_id = ? LIMIT 1", [$courseId, $taId], "ii")) {
        db_query("INSERT INTO course_tas (course_id, ta_id) VALUES (?, ?)", [$courseId, $taId], "ii");
    }

    foreach ([[$s1, "active"], [$s2, "active"], [$s3, "pending"]] as $row) {
        if (!db_fetch_one("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? LIMIT 1", [$row[0], $courseId], "ii")) {
            db_query("INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, ?)", [$row[0], $courseId, $row[1]], "iis");
        }
    }

    $quiz1 = seed_quiz($courseId, $instructorId, "Stack and Queue Basics", "Basic MCQ quiz on stack and queue.", 20, 10, 5, "graded", "published");
    $quiz2 = seed_quiz($courseId, $instructorId, "Tree Traversal Practice", "Practice quiz on tree traversals.", 15, 10, 5, "practice", "draft");

    seed_question($quiz1, "Which data structure follows LIFO?", 2, 1, $instructorId, [
        ["Queue", 0], ["Stack", 1], ["Tree", 0], ["Graph", 0]
    ]);

    seed_question($quiz1, "Which data structure follows FIFO?", 2, 2, $instructorId, [
        ["Stack", 0], ["Queue", 1], ["Heap", 0], ["Array", 0]
    ]);

    seed_question($quiz2, "Which tree traversal visits root first?", 2, 1, $instructorId, [
        ["Inorder", 0], ["Preorder", 1], ["Postorder", 0], ["Level Order", 0]
    ]);

    if (!db_fetch_one("SELECT id FROM attempts WHERE quiz_id = ? AND student_id = ? LIMIT 1", [$quiz1, $s1], "ii")) {
        db_query(
            "INSERT INTO attempts (quiz_id, student_id, score, started_at, completed_at, is_graded)
             VALUES (?, ?, 8, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 1)",
            [$quiz1, $s1],
            "ii"
        );
    }

    if (!db_fetch_one("SELECT id FROM attempts WHERE quiz_id = ? AND student_id = ? LIMIT 1", [$quiz1, $s2], "ii")) {
        db_query(
            "INSERT INTO attempts (quiz_id, student_id, score, started_at, completed_at, is_graded)
             VALUES (?, ?, 4, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 1)",
            [$quiz1, $s2],
            "ii"
        );
    }

    if (!db_fetch_one("SELECT id FROM announcements WHERE course_id = ? AND title = ? LIMIT 1", [$courseId, "Welcome to Data Structures"], "is")) {
        db_query(
            "INSERT INTO announcements (course_id, author_id, title, body) VALUES (?, ?, ?, ?)",
            [$courseId, $instructorId, "Welcome to Data Structures", "Please read the course outline and complete the first quiz."],
            "iiss"
        );
    }

    if (!db_fetch_one("SELECT id FROM course_materials WHERE course_id = ? AND title = ? LIMIT 1", [$courseId, "Stack Cheat Sheet"], "is")) {
        db_query(
            "INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type) VALUES (?, ?, ?, ?, ?)",
            [$courseId, $instructorId, "Stack Cheat Sheet", "uploads/demo-stack-cheatsheet.pdf", "document"],
            "iisss"
        );
    }

    $qa = db_fetch_one("SELECT id FROM qa_questions WHERE course_id = ? AND title = ? LIMIT 1", [$courseId, "Stack vs Queue"], "is");
    if (!$qa) {
        db_query(
            "INSERT INTO qa_questions (course_id, student_id, title, body, is_resolved) VALUES (?, ?, ?, ?, 0)",
            [$courseId, $s2, "Stack vs Queue", "What is the main difference between stack and queue?"],
            "iiss"
        );
        $qaId = $conn->insert_id;

        db_query(
            "INSERT INTO qa_answers (qa_question_id, author_id, body, is_endorsed) VALUES (?, ?, ?, 1)",
            [$qaId, $instructorId, "Stack follows LIFO and queue follows FIFO."],
            "iis"
        );
    }

    if (!db_fetch_one("SELECT id FROM doubt_sessions WHERE course_id = ? AND title = ? LIMIT 1", [$courseId, "Stack and Queue Doubt Session"], "is")) {
        db_query(
            "INSERT INTO doubt_sessions (course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees)
             VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 3 DAY), ?, ?, ?)",
            [$courseId, $taId, "Stack and Queue Doubt Session", 60, "Google Meet", 20],
            "iisisi"
        );
        $sessionId = $conn->insert_id;

        db_query("INSERT INTO doubt_session_bookings (doubt_session_id, student_id) VALUES (?, ?)", [$sessionId, $s1], "ii");
        db_query("INSERT INTO doubt_session_bookings (doubt_session_id, student_id) VALUES (?, ?)", [$sessionId, $s2], "ii");
    }
}

initialize_database();
?>
