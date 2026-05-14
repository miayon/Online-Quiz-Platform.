**Project --- Online Quiz & Exam Platform**

**Overview**

A multi-role online learning and assessment platform. Students enrol in
courses and take quizzes with live scoring and performance tracking.
Instructors build course content and analyse results. Teaching
Assistants support course delivery by creating practice material,
monitoring struggling students, and managing doubt sessions. The
platform admin governs users, courses, and academic integrity across the
institution.

**Roles**

  -----------------------------------------------------------------------
  **Role**                    **Responsibility**
  --------------------------- -------------------------------------------
  **Student**                 Enrolment, quiz-taking, results, performance
                              tracking

  **Instructor**              Course and quiz creation, grading analytics,
                              materials

  **Teaching Assistant**      Practice quizzes, student monitoring, doubt
                              sessions

  **Admin**                   User management, platform settings, institutional
                              reports
  -----------------------------------------------------------------------

**Shared Database Schema**

All four students create and use the same database. The schema must
include at minimum the following tables:

**users** --- All platform users

-   id, name, email, password_hash, phone, role (student/instructor/ta/admin), profile_pic, student_id, program, is_active, created_at

**subjects** --- Top-level academic subjects (e.g., Mathematics,
Computer Science)

-   id, name, description

**courses** --- Courses created by instructors

-   id, instructor_id, subject_id, title, description, enrollment_type (open/approval), max_students, status (draft/active/archived), created_at

**course_tas** --- Teaching assistants assigned to a course

-   id, course_id, ta_id, assigned_at

**enrollments** --- Student enrolment in courses

-   id, student_id, course_id, status (pending/active/dropped), enrolled_at

**quizzes** --- Quizzes and practice tests within a course

-   id, course_id, created_by, title, description, time_limit_minutes, total_marks, pass_mark, quiz_type (graded/practice), status (draft/published), available_from, available_until

**questions** --- MCQ questions belonging to quizzes

-   id, quiz_id, question_text, marks, order_index, created_by

**options** --- Answer options for each question

-   id, question_id, option_text, is_correct

**attempts** --- Student quiz attempts

-   id, quiz_id, student_id, score, started_at, completed_at, is_graded

**answers** --- Individual answers within an attempt

-   id, attempt_id, question_id, selected_option_id

**course_materials** --- Files and links uploaded for a course

-   id, course_id, uploaded_by, title, file_path, material_type (document/link/video), created_at

**announcements** --- Course-level announcements

-   id, course_id, author_id, title, body, created_at

**qa_questions** --- Student questions in the course Q&A board

-   id, course_id, student_id, title, body, is_resolved, created_at

**qa_answers** --- Replies to Q&A board questions

-   id, qa_question_id, author_id, body, is_endorsed, created_at

**doubt_sessions** --- TA-scheduled live doubt sessions

-   id, course_id, ta_id, title, scheduled_at, duration_minutes, location_or_link, max_attendees

**doubt_session_bookings** --- Students who have booked a doubt session
slot

-   id, doubt_session_id, student_id, booked_at

***Each group member is responsible for creating their own data in the
database. Each member may only insert the data that is required for
demonstrating their own work***

**Technical Requirements**

-   Follow the **MVC pattern**: separate Models, Views, and Controllers;
    no business logic in view files

-   Use **PHP** for all server-side logic and **MySQL** as the database

-   All database queries must use **mysqli with prepared
    statements** --- no raw string insertion allowed

-   Authentication must use **PHP sessions** with role-based access
    control on every protected page

-   Each student must implement **at least one feature using
    AJAX** (XMLHttpRequest) communicating with a PHP API endpoint that
    returns JSON

-   Maintain a central repository for the group where each student will
    push their code. It is the responsibility of each group member to
    ensure their code works when cloned and run in a XAMPP Apache
    server.

-   Use **Git** on the command line for version control. Do not push to
    main/master branch directly. Create feature branches and submit a
    Pull Request for each major feature

-   Submit: (1) working online codebase, (2) hardcopy report describing
    your role\'s features

**Role 1 --- Student**

**Description**

Students are the learners on the platform. They enrol in courses,
consume study materials, interact with the Q&A board, take graded and
practice quizzes, review their results, and track their academic
progress over time.

**Features**

-   Register with name, email, student ID, program, and password; log in
    and log out

-   Manage profile: update personal information, upload profile picture,
    change password

-   Browse all active courses by subject; search by keyword; view course
    description, instructor name, and enrolled student count

-   Enrol in open-enrollment courses directly; submit an enrolment
    request for approval-based courses

-   View all enrolled courses on a personal dashboard with course status
    and next upcoming quiz

-   View enrolled course detail: description, instructor, assigned TA,
    announcements, materials, and quizzes

-   Access and download course materials (documents, links, videos)
    uploaded by the instructor or TA

-   View and read all course announcements

-   Browse the course Q&A board; post a new question (title + body);
    view all questions and their answers

-   Mark own Q&A question as resolved; view questions answered by
    instructors or TAs with endorsement badges

-   View all published graded quizzes for enrolled courses; take a quiz
    (MCQ, single page, countdown timer)

-   View quiz result immediately after submission: score, pass/fail, and
    question-by-question breakdown

-   Take unlimited attempts on practice quizzes; view results after each
    attempt

-   View complete attempt history for all quizzes with scores and
    timestamps

-   View course leaderboard (top students by score for a specific quiz)
    via AJAX

-   View personal performance dashboard: scores over time, pass rate,
    average score per subject, and comparison to class average

-   Book a slot in a TA\'s doubt session; view all upcoming booked doubt
    sessions

-   Drop a course (if no graded quiz has been completed in it)

**Role 2 --- Instructor**

**Description**

Instructors create and manage courses, build quiz content, upload
learning resources, and track the academic performance of their
students. They engage students through the Q&A board and course
announcements.

**Features**

-   Log in (account created by admin); manage professional profile:
    name, department, bio, profile picture

-   Create a new course: title, subject, description, enrollment type
    (open/approval), and maximum student count; save as draft or publish

-   Manage course details: edit all fields, archive a completed course;
    view enrolled student list

-   Manage course enrollment requests: approve or reject pending
    enrollment requests

-   Assign a Teaching Assistant to a course from the list of TA accounts

-   Create quizzes for their course: title, description, time limit
    (minutes), total marks, pass mark, type (graded/practice), and
    availability window (from date to until date)

-   Add MCQ questions to a quiz: question text, marks, and four answer
    options with the correct one marked

-   Manage a course-level question bank: edit, delete, or reuse
    questions across multiple quizzes

-   Publish or unpublish quizzes; view all quizzes with attempt counts
    and average scores

-   View all student attempts for a specific quiz: student name, score,
    duration, and pass/fail

-   View grade analytics for each quiz: class average, highest and
    lowest score, score distribution, pass rate

-   Post course announcements: title and body; view all past
    announcements

-   Upload course materials: title, file upload or external link, and
    material type

-   Manage uploaded materials: edit title, replace file, or delete

-   Answer and endorse answers on the course Q&A board; mark Q&A
    questions as resolved

-   View overall course performance report: enrolled students, quiz
    completion rates, average scores per quiz, drop-out rate

**Role 3 --- Teaching Assistant**

**Description**

Teaching Assistants support the instructor in delivering the course.
They create practice quizzes, monitor student progress to identify those
who are struggling, manage the course Q&A board, upload supplementary
resources, and schedule doubt sessions for students.

**Features**

-   Log in and manage profile; view all courses they are assigned to

-   View course details, enrolled students, and all quizzes for each
    assigned course

-   Create practice quizzes for an assigned course (quiz type =
    practice, requires instructor approval before appearing to students)

-   Manage question bank for assigned courses: add, edit, and delete
    questions and options

-   View all student attempt results for their assigned courses:
    individual scores, timestamps, and pass/fail status

-   Identify and flag at-risk students: view a filtered list of students
    whose quiz scores fall below a configurable threshold; flag them for
    instructor review

-   Send a course announcement on behalf of the instructor (marked as
    \"From TA\")

-   Upload supplementary study materials for an assigned course: notes,
    cheat-sheets, additional references

-   Manage uploaded materials: edit or delete their own uploads

-   Monitor and respond to student questions on the course Q&A board

-   Endorse particularly helpful answers posted by students or other TAs

-   Schedule doubt sessions: create a session with title, date/time,
    duration, location or meeting link, and maximum attendees

-   View all bookings for each doubt session; view list of attending
    students

-   Cancel or reschedule a doubt session with a notice to all booked
    students

-   View a summary report for each assigned course: total students, quiz
    attempt rates, average score, number of at-risk students

**Role 4 --- Admin**

**Description**

The Admin manages the entire platform: user accounts, course catalog
oversight, academic integrity enforcement, and institutional reporting.
They are the only user who can approve instructor accounts and promote
users to TA or admin roles.

**Features**

-   Log in to an admin dashboard: total users (by role), total active
    courses, total quiz attempts today, and total pending instructor
    approval requests

-   Manage all user accounts: search, view, activate, deactivate, and
    change roles for students, instructors, TAs, and other admins

-   Approve or reject new instructor registration requests; create TA
    accounts directly

-   View all courses across the platform: active, draft, and archived;
    filter by subject and instructor

-   Manage platform-wide subject taxonomy: add, rename, and delete
    subjects

-   View all quizzes across the platform: filter by course, status, or
    type; view attempt counts

-   Handle reported content or academic integrity flags: view reports,
    review details, and mark as resolved or escalated

-   View platform-wide analytics: total enrollments per subject, quiz
    pass rates across all courses, most active instructors, peak usage
    times

-   Manage platform announcements visible to all users

-   View a student academic report: per-student summary of all courses,
    attempt counts, and average scores (searchable by student ID or
    name)

-   Generate institutional report: total users, active courses, quizzes,
    attempts, and pass rates per subject for a selected semester range

-   Set platform-wide policies: maximum quiz duration, maximum students
    per course default, and other configurable parameters

-   View audit log of significant admin actions

**Submission Checklist**

-    Each group member is responsible that each of their roles are
    individually accessible with their own login and dashboard

-    Role-based access control prevents any user from accessing another
    role\'s pages

-    The shared database schema is implemented correctly and
    consistently across all roles

-    At least one AJAX-based feature is implemented per role

-    All forms include server-side validation with descriptive error
    messages

-    Git history shows feature branches and pull requests for major
    features

-    Hardcopy report describes all features for your assigned role

**Separation of Concerns**

-   Each group member is responsible for their own code and submission.
    Other members work will not affect one\'s development and
    submission.

-   DO NOT rely on any other of your group member for anything,
    including DB table creation, data insertion, session management etc.

**Faculty Instructions**

Each group member will be responsible for one of the 4 roles. You can discuss between your teammates to decide who implements which role (for groups of 3 members, any 3 out of 4 roles can be chosen)
 
This is an individually evaluated project: you are only responsible for your part and nothing else. The projects are defined so that group collaboration is encouraged but not necessary. If your group members are not cooperative, you can implement your part by yourself.
 
Group members are encouraged to collaborate in the following ways:
Create a shared Github repository: each member creates feature branches for developing major features and create pull requests to the main/master branch after each feature is implemented. Any member/group leader can merge the pull requests.
BUT IF your group members are not collaborating with you, then you will create and show your own pull requests from your own feature branches in your own repository.
Create a shared database schema SQL script which every member can use to setup their database:
BUT IF your group members are not collaborating with you, then you will create and show your own database tables and inserted data.
Group member are not allowed to collaborate by:
Helping each other in developing the features