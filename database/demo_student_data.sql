-- Demo data for testing Student role.
-- Run this after creating the main schema in database: web_project

INSERT INTO users 
(name, email, password_hash, role, is_active)
VALUES
('Sarah TA', 'ta@quiz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ta', 1)
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO courses 
(instructor_id, subject_id, title, description, enrollment_type, max_students, status)
VALUES
(3, 1, 'Web Engineering', 'Learn PHP, MySQL, MVC and web application development.', 'open', 50, 'active'),
(3, 2, 'Discrete Mathematics', 'Logic, sets, relations and graph theory basics.', 'open', 40, 'active');

INSERT INTO course_tas (course_id, ta_id)
VALUES
(1, 4),
(2, 4);

INSERT INTO enrollments (student_id, course_id, status)
VALUES
(1, 1, 'active'),
(1, 2, 'active');

INSERT INTO quizzes
(course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type, status, available_from, available_until)
VALUES
(1, 3, 'PHP Basics Quiz', 'Basic PHP MCQ quiz.', 10, 10, 5, 'graded', 'published', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)),
(2, 3, 'Logic Practice Quiz', 'Practice quiz on logic.', 15, 10, 5, 'practice', 'published', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY));

INSERT INTO questions
(quiz_id, question_text, marks, order_index, created_by)
VALUES
(1, 'What does PHP stand for?', 5, 1, 3),
(1, 'Which symbol is used before variables in PHP?', 5, 2, 3),
(2, 'Which one is a logical operator?', 5, 1, 3),
(2, 'A proposition is a statement that is either true or false.', 5, 2, 3);

INSERT INTO options
(question_id, option_text, is_correct)
VALUES
(1, 'Personal Home Page', 0),
(1, 'PHP: Hypertext Preprocessor', 1),
(1, 'Private Hypertext Processor', 0),
(1, 'Public Home Page', 0),
(2, '#', 0),
(2, '$', 1),
(2, '@', 0),
(2, '&', 0),
(3, 'AND', 1),
(3, 'SUM', 0),
(3, 'AVG', 0),
(3, 'COUNT', 0),
(4, 'True', 1),
(4, 'False', 0),
(4, 'Maybe', 0),
(4, 'None', 0);

INSERT INTO course_materials
(course_id, uploaded_by, title, file_path, material_type)
VALUES
(1, 3, 'PHP Official Documentation', 'https://www.php.net/docs.php', 'link'),
(1, 4, 'MVC Notes', 'mvc_notes.pdf', 'document'),
(2, 3, 'Logic Reference Video', 'https://www.youtube.com/', 'video');

INSERT INTO announcements
(course_id, author_id, title, body)
VALUES
(1, 3, 'PHP Quiz Published', 'The PHP Basics Quiz is now available. Please complete it this week.'),
(2, 3, 'Practice Quiz Available', 'A practice quiz on logic has been published.');

INSERT INTO qa_questions
(course_id, student_id, title, body, is_resolved)
VALUES
(1, 1, 'What is MVC?', 'Can anyone explain MVC pattern in simple words?', 0);

INSERT INTO qa_answers
(qa_question_id, author_id, body, is_endorsed)
VALUES
(1, 4, 'MVC means Model, View, Controller. Model handles database, View handles UI, Controller handles logic.', 1);

INSERT INTO doubt_sessions
(course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees)
VALUES
(1, 4, 'PHP Doubt Clearing Session', DATE_ADD(NOW(), INTERVAL 2 DAY), 60, 'https://meet.google.com/demo-php', 10),
(2, 4, 'Logic Help Session', DATE_ADD(NOW(), INTERVAL 3 DAY), 45, 'Room 204', 8);
