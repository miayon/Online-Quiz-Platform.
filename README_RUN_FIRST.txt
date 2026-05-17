Instructor MVC Full Project

This is a complete Instructor module following the GitHub-style structure:

api/
assets/css/
assets/js/
config/
controllers/
models/
views/
uploads/
database.sql
index.php
login.php

How to run:
1. Put this full folder inside:
   C:\xampp\htdocs\instructor_mvc_full_project

2. Start Apache and MySQL in XAMPP.

3. Open:
   http://localhost/instructor_mvc_full_project/login.php

4. Login:
   Email: instructor@quiz.com
   Password: password

No manual SQL import is required.
config/db.php automatically creates:
- database online_quiz_platform
- all shared project tables
- demo instructor
- demo TA
- demo students
- demo course
- demo quizzes
- demo attempts
- demo announcements/materials/Q&A

Important:
Do not open controller files directly.
Open login.php, then use the dashboard and menu links.
