CREATE DATABASE IF NOT EXISTS online_quiz_platform;
USE online_quiz_platform;

DROP TABLE IF EXISTS ta_doubt_session_status;
DROP TABLE IF EXISTS ta_student_flags;
DROP TABLE IF EXISTS doubt_session_bookings;
DROP TABLE IF EXISTS doubt_sessions;
DROP TABLE IF EXISTS qa_answers;
DROP TABLE IF EXISTS qa_questions;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS course_materials;
DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS attempts;
DROP TABLE IF EXISTS options;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS quizzes;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS course_tas;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS integrity_reports;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS platform_settings;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password_hash VARCHAR(255),
  phone VARCHAR(20),
  role VARCHAR(20) DEFAULT 'student',
  profile_pic VARCHAR(255),
  student_id VARCHAR(50),
  program VARCHAR(100),
  is_active TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subjects (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  description TEXT
);

CREATE TABLE courses (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  instructor_id INT(11),
  subject_id INT(11),
  title VARCHAR(150),
  description TEXT,
  enrollment_type VARCHAR(20) DEFAULT 'open',
  max_students INT(11) DEFAULT 50,
  status VARCHAR(20) DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE course_tas (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  ta_id INT(11),
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE enrollments (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  student_id INT(11),
  course_id INT(11),
  status VARCHAR(20) DEFAULT 'pending',
  enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quizzes (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  created_by INT(11),
  title VARCHAR(150),
  description TEXT,
  time_limit_minutes INT(11),
  total_marks INT(11),
  pass_mark INT(11),
  quiz_type VARCHAR(50) DEFAULT 'graded',
  status VARCHAR(50) DEFAULT 'draft',
  available_from DATETIME,
  available_until DATETIME
);

CREATE TABLE questions (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT(11),
  question_text TEXT,
  marks INT(11),
  order_index INT(11),
  created_by INT(11)
);

CREATE TABLE options (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  question_id INT(11),
  option_text VARCHAR(255),
  is_correct INT(11) DEFAULT 0
);

CREATE TABLE attempts (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT(11),
  student_id INT(11),
  score INT(11) DEFAULT 0,
  started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_at TIMESTAMP NULL,
  is_graded INT(11) DEFAULT 1
);

CREATE TABLE answers (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  attempt_id INT(11),
  question_id INT(11),
  selected_option_id INT(11)
);

CREATE TABLE course_materials (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  uploaded_by INT(11),
  title VARCHAR(150),
  file_path VARCHAR(255),
  material_type VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE announcements (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  author_id INT(11),
  title VARCHAR(150),
  body TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE qa_questions (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  student_id INT(11),
  title VARCHAR(150),
  body TEXT,
  is_resolved INT(11) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE qa_answers (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  qa_question_id INT(11),
  author_id INT(11),
  body TEXT,
  is_endorsed INT(11) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE doubt_sessions (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11),
  ta_id INT(11),
  title VARCHAR(150),
  scheduled_at DATETIME,
  duration_minutes INT(11),
  location_or_link VARCHAR(255),
  max_attendees INT(11) DEFAULT 10
);

CREATE TABLE doubt_session_bookings (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  doubt_session_id INT(11),
  student_id INT(11),
  booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11),
  action VARCHAR(255),
  details TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE integrity_reports (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  reported_by INT(11),
  quiz_id INT(11),
  student_id INT(11),
  reason TEXT,
  status VARCHAR(20) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE platform_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value VARCHAR(255)
);

CREATE TABLE ta_student_flags (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  course_id INT(11) NOT NULL,
  student_id INT(11) NOT NULL,
  attempt_id INT(11) NOT NULL,
  ta_id INT(11) NOT NULL,
  reason TEXT,
  status VARCHAR(50) DEFAULT 'pending_review',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ta_doubt_session_status (
  doubt_session_id INT(11) PRIMARY KEY,
  status VARCHAR(50) DEFAULT 'scheduled',
  notice TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password_hash, phone, role, student_id, program, is_active) VALUES
('Teaching Assistant', 'ta@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801000000000', 'ta', NULL, 'Computer Science', 1),
('John Instructor', 'john@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801000000001', 'instructor', NULL, 'Computer Science', 1),
('Ayesha Khan', 'ayesha@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801111111111', 'student', 'S-101', 'CSE', 1),
('Nafis Ahmed', 'nafis@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801222222222', 'student', 'S-102', 'CSE', 1),
('Tania Islam', 'tania@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+8801333333333', 'student', 'S-103', 'CSE', 1);

INSERT INTO subjects (name, description) VALUES
('Computer Science', 'CSE related subjects'),
('Mathematics', 'Math related subjects');

SET @ta_id = (SELECT id FROM users WHERE email = 'ta@quiz.com');
SET @instructor_id = (SELECT id FROM users WHERE email = 'john@quiz.com');
SET @subject_id = (SELECT id FROM subjects WHERE name = 'Computer Science');

INSERT INTO courses (instructor_id, subject_id, title, description, enrollment_type, max_students, status)
VALUES
(@instructor_id, @subject_id, 'Data Structures', 'Stacks, queues, trees, graphs and basic algorithmic thinking.', 'open', 60, 'active');

SET @course_id = LAST_INSERT_ID();

INSERT INTO course_tas (course_id, ta_id) VALUES (@course_id, @ta_id);

SET @s1 = (SELECT id FROM users WHERE email = 'ayesha@student.com');
SET @s2 = (SELECT id FROM users WHERE email = 'nafis@student.com');
SET @s3 = (SELECT id FROM users WHERE email = 'tania@student.com');

INSERT INTO enrollments (student_id, course_id, status) VALUES
(@s1, @course_id, 'active'),
(@s2, @course_id, 'active'),
(@s3, @course_id, 'active');

INSERT INTO quizzes (course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until) VALUES
(@course_id, @instructor_id, 'Stack and Queue Basics', 'Basic MCQ quiz on stack and queue.', 20, 10, 5, 'graded', 'published', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)),
(@course_id, @ta_id, 'Tree Traversal Practice', 'TA-created practice quiz awaiting instructor approval.', 15, 10, 5, 'practice', 'pending_approval', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY));

SET @quiz1 = (SELECT id FROM quizzes WHERE title = 'Stack and Queue Basics' LIMIT 1);
SET @quiz2 = (SELECT id FROM quizzes WHERE title = 'Tree Traversal Practice' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, marks, order_index, created_by) VALUES
(@quiz1, 'Which data structure follows LIFO?', 2, 1, @instructor_id),
(@quiz1, 'Which data structure follows FIFO?', 2, 2, @instructor_id),
(@quiz2, 'Which tree traversal visits root first?', 2, 1, @ta_id);

SET @q1 = (SELECT id FROM questions WHERE question_text = 'Which data structure follows LIFO?' LIMIT 1);
SET @q2 = (SELECT id FROM questions WHERE question_text = 'Which data structure follows FIFO?' LIMIT 1);
SET @q3 = (SELECT id FROM questions WHERE question_text = 'Which tree traversal visits root first?' LIMIT 1);

INSERT INTO options (question_id, option_text, is_correct) VALUES
(@q1, 'Queue', 0), (@q1, 'Stack', 1), (@q1, 'Tree', 0), (@q1, 'Graph', 0),
(@q2, 'Stack', 0), (@q2, 'Queue', 1), (@q2, 'Heap', 0), (@q2, 'Array', 0),
(@q3, 'Inorder', 0), (@q3, 'Preorder', 1), (@q3, 'Postorder', 0), (@q3, 'Level Order', 0);

INSERT INTO attempts (quiz_id, student_id, score, started_at, completed_at, is_graded) VALUES
(@quiz1, @s1, 8, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 1),
(@quiz1, @s2, 4, DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 1),
(@quiz1, @s3, 6, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 1);

INSERT INTO announcements (course_id, author_id, title, body) VALUES
(@course_id, @ta_id, 'Extra Practice Added', '[From TA] Please check the new practice materials for stacks and queues.');

INSERT INTO qa_questions (course_id, student_id, title, body, is_resolved) VALUES
(@course_id, @s2, 'Stack vs Queue', 'What is the main difference between stack and queue?', 0),
(@course_id, @s3, 'Tree traversal confusion', 'I do not understand preorder traversal clearly.', 0);

SET @qa1 = (SELECT id FROM qa_questions WHERE title = 'Stack vs Queue' LIMIT 1);

INSERT INTO qa_answers (qa_question_id, author_id, body, is_endorsed) VALUES
(@qa1, @ta_id, 'Stack follows LIFO and queue follows FIFO.', 1);

INSERT INTO course_materials (course_id, uploaded_by, title, file_path, material_type) VALUES
(@course_id, @ta_id, 'Stack Cheat Sheet', 'uploads/demo-stack-cheatsheet.pdf', 'document');

INSERT INTO doubt_sessions (course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees) VALUES
(@course_id, @ta_id, 'Stack and Queue Doubt Session', DATE_ADD(NOW(), INTERVAL 3 DAY), 60, 'Google Meet', 20);

SET @session_id = LAST_INSERT_ID();

INSERT INTO ta_doubt_session_status (doubt_session_id, status, notice) VALUES (@session_id, 'scheduled', '');

INSERT INTO doubt_session_bookings (doubt_session_id, student_id) VALUES
(@session_id, @s1),
(@session_id, @s2);

INSERT INTO platform_settings (setting_key, setting_value) VALUES
('ta_at_risk_threshold', '50'),
('max_quiz_duration', '120'),
('max_students_default', '50'),
('allow_instructor_registration', '1');
