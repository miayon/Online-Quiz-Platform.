-- Simplified Database Schema for Online Quiz Platform

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

-- Insert Default Data
INSERT INTO users (name, email, password_hash, role, is_active) VALUES 
('Rakib', 'rakib@gmail.com', '$2y$10$7Av5NTHkkCui/C1FBSGGi.u3XwQaqykfN.xKGd9Nv86/5lkI/SzpC', 'student', 1),
('Admin User', 'admin@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('John Instructor', 'john@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 1);

INSERT INTO subjects (name, description) VALUES 
('Computer Science', 'CSE related subjects'),
('Mathematics', 'Math related subjects');

INSERT INTO platform_settings (setting_key, setting_value) VALUES
('max_quiz_duration', '120'),
('max_students_default', '50'),
('allow_instructor_registration', '1');
