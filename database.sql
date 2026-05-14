-- Database: online_quiz_platform

CREATE DATABASE IF NOT EXISTS online_quiz_platform;
USE online_quiz_platform;

-- users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('student', 'instructor', 'ta', 'admin') NOT NULL,
    profile_pic VARCHAR(255),
    student_id VARCHAR(20),
    program VARCHAR(100),
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- subjects table
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT,
    subject_id INT,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    enrollment_type ENUM('open', 'approval') DEFAULT 'open',
    max_students INT DEFAULT 50,
    status ENUM('draft', 'active', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- course_tas table
CREATE TABLE IF NOT EXISTS course_tas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    ta_id INT,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (ta_id) REFERENCES users(id) ON DELETE CASCADE
);

-- enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    status ENUM('pending', 'active', 'dropped') DEFAULT 'pending',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- quizzes table
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    created_by INT,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    time_limit_minutes INT,
    total_marks INT,
    pass_mark INT,
    quiz_type ENUM('graded', 'practice') DEFAULT 'graded',
    status ENUM('draft', 'published') DEFAULT 'draft',
    available_from DATETIME,
    available_until DATETIME,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- questions table
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question_text TEXT NOT NULL,
    marks INT DEFAULT 1,
    order_index INT,
    created_by INT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- options table
CREATE TABLE IF NOT EXISTS options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    option_text TEXT NOT NULL,
    is_correct TINYINT(1) DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- attempts table
CREATE TABLE IF NOT EXISTS attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    student_id INT,
    score INT DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at DATETIME,
    is_graded TINYINT(1) DEFAULT 0,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- answers table
CREATE TABLE IF NOT EXISTS answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT,
    question_id INT,
    selected_option_id INT,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (selected_option_id) REFERENCES options(id) ON DELETE CASCADE
);

-- course_materials table
CREATE TABLE IF NOT EXISTS course_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    uploaded_by INT,
    title VARCHAR(150) NOT NULL,
    file_path VARCHAR(255),
    material_type ENUM('document', 'link', 'video') DEFAULT 'document',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT, -- NULL if platform-wide
    author_id INT,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- qa_questions table
CREATE TABLE IF NOT EXISTS qa_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    student_id INT,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    is_resolved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- qa_answers table
CREATE TABLE IF NOT EXISTS qa_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qa_question_id INT,
    author_id INT,
    body TEXT NOT NULL,
    is_endorsed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (qa_question_id) REFERENCES qa_questions(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- doubt_sessions table
CREATE TABLE IF NOT EXISTS doubt_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    ta_id INT,
    title VARCHAR(150) NOT NULL,
    scheduled_at DATETIME NOT NULL,
    duration_minutes INT,
    location_or_link VARCHAR(255),
    max_attendees INT DEFAULT 10,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (ta_id) REFERENCES users(id) ON DELETE CASCADE
);

-- doubt_session_bookings table
CREATE TABLE IF NOT EXISTS doubt_session_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doubt_session_id INT,
    student_id INT,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doubt_session_id) REFERENCES doubt_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- audit_logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert a default admin account
-- Password is 'admin123'
INSERT INTO users (name, email, password_hash, role, is_active) 
VALUES ('Admin User', 'admin@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Dummy Data for testing oversight
INSERT INTO users (name, email, password_hash, role, is_active) VALUES 
('John Instructor', 'john@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 1),
('Jane Student', 'jane@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 1),
('Mark TA', 'mark@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ta', 1);

INSERT INTO subjects (name, description) VALUES 
('Web Technologies', 'Learning HTML, CSS, PHP and MySQL'),
('Mathematics', 'Discrete and Linear Algebra');

INSERT INTO courses (instructor_id, subject_id, title, description, status) VALUES 
(2, 1, 'Advanced Web Tech', 'Mastering MVC and AJAX', 'active'),
(2, 2, 'Basic Math', 'Introduction to Algebra', 'draft');

INSERT INTO quizzes (course_id, created_by, title, total_marks, pass_mark, status) VALUES 
(1, 2, 'PHP Basics Quiz', 20, 10, 'published'),
(1, 4, 'Practice Lab 1', 10, 5, 'published');

INSERT INTO enrollments (student_id, course_id, status) VALUES (3, 1, 'active');
