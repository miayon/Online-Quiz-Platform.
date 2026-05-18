# QuizlyX — Secure Online Quiz & Exam Platform

QuizlyX is a secure, responsive, and role separated academic evaluation platform. It digitizes the assessment lifecycle, providing robust portals for Students, Instructors, Teaching Assistants (TAs) and Platform Administrators. The application is built entirely upon a decoupled Model-View-Controller (MVC) architecture using PHP, MySQL and clean HTML5/CSS3.

---

##  Isolated Multi-Role Portals

###  1. Student Panel
* **Assessment Engine:** Take graded or unlimited practice quizzes featuring responsive layout interfaces and dynamic client side countdown timers.
* **Academic Insights:** View immediate performance breakdowns, score histories, subject distributions and class average comparisons.
* **Engagement Boards:** Post questions in Q&A forums, mark doubt threads as resolved and book direct slots in TA-scheduled doubt sessions.

###  2. Instructor Panel
* **Curriculum Design:** Formulate courses (open or approval based), design quizzes and create a comprehensive reusable MCQ Question Bank.
* **Evaluation Analytics:** Moderating enrollment requests, tracking average class scores, uploader interfaces for course materials and posting announcements.
* **Class Insights:** View grading distribution curves, pass/fail rates and individual quiz performance summaries.

###  3. Teaching Assistant (TA) Panel
* **Academic Support:** Schedule live doubt sessions with student booking trackers and upload supplementary cheat-sheets or cheat guides.
* **Content Creation:** Formulate course practice quizzes (subject to instructor review) and contribute to the course question bank.
* **At-Risk Monitoring:** Identify and flag struggling students whose quiz scores fall below institutional thresholds for instructor intervention.

###  4. Admin Panel
* **User Governance:** Audit user credentials, toggle account statuses (activate/suspend) and approve pending Instructor registration requests.
* **System Settings:** Configure global platform policies (e.g., maximum quiz duration, course default limits and subject taxonomy management).
* **Security & Audits:** Monitor institutional analytics, view a custom vanilla CSS dynamic bar chart of peak activity traffic hours and audit administrative actions in an immutable security log.

---

##  Technical Design & Security Baseline

* **Strict MVC Pattern:** Decoupled architecture separating database models (`models/`), layout views (`views/`) and routing processing gates (`controllers/`).
* **Session-Based RBAC:** Secure, server side Role Based Access Control validation executed on every protected page.
* **Prepared SQL Queries:** 100% prepared MySQLi statement queries to eliminate SQL Injection (SQLi) vulnerabilities.
* **AJAX Async Features:** Asynchronous UI data rendering (e.g., dashboard stats and interactive user filters) utilizing native Javascript XMLHttpRequests communicating with PHP JSON API endpoints.
* **Dynamic Styling:** Rich dark/light mode accents, glassmorphic dashboards  and custom dynamic CSS charts built purely with vanilla CSS variables and flexbox elements.

---

##  Installation & Setup Guide

### System Requirements
* XAMPP (running Apache & MySQL with PHP 8.0+)
* Git Version Control

### Step-by-Step Launch
1. Clone this repository directly into your local XAMPP web directory:
   ```bash
   git clone https://github.com/miayon/Online-Quiz-Platform.git C:\xampp\htdocs\online_quiz_platform
   ```
2. Open the **XAMPP Control Panel** and start both **Apache** and **MySQL** services.
3. Open your browser and run the unified migrator script to auto-generate the database, build all shared tables, and seed rich academic demo records:
    **`http://localhost/online_quiz_platform/config/migrate.php`**
4. Open the login gateway to explore the portal:
    **`http://localhost/online_quiz_platform/login.php`**

---

##  Demo Access Accounts

All seeded portals utilize the standard password `password` for easy local testing:

| Role Panel              | Access Username         | Password        |
| :---                    | :---                    | :---            |
|  **Platform Admin**     | `admin@quiz.com`        | `password`      |
|  **Instructor**         | `john@quiz.com`         | `password`      |
|  **Student**            | `rakib@gmail.com`       | `123456`        |
|  **Teaching Assistant** | `newta@quiz.com`        | `password123`   |

---

##  Git & Feature Isolation Workflow

QuizlyX development strictly prioritized academic codebase integrity through isolated **Git Feature Branches** and structured peer-reviewed **Pull Requests (PRs)**:

### Admin Branches
* `feature-admin-user-management` — Governed user account moderation, activation/deactivation toggles and instructor registration approvals.
* `feature-admin-content-oversight` — Managed platform wide course/quiz monitoring boards and resolved escalated academic integrity violations.
* `feature-admin-governance` — Managed global taxonomy settings, institutional platform policies, system announcements and immutable system audit logs.
* `feature-admin-academic-reporting` — Built high-level reports, dynamic enrollment analytics, and custom dynamic peak-hour bar charts.

###  Instructor Branch
* `instructor-feature` — Developed course builder wizards, MCQ question banks, graded assessment systems, study resource uploaders, Q&A reply endorsement trackers, and course analytics reports.

###  Student Branch
* `student-feature` — Governed self-registrations, subject browsers, timed quiz-taking sheets, attempt score historical dashboards, course leaderboards and doubt session slot bookings.

###  Teaching Assistant (TA) Branch
* `tanveerfahim` — Created practice quiz templates, TA announcement modules, supplementary cheatsheet uploaders, doubt session calendars and at-risk student threshold flagging controls.

###  Integration Protocol
* To maintain role isolation, development was performed strictly in local sandboxes. Features were pushed to remote branches on GitHub, reviewed via **Pull Requests (PRs)**, and merged into the stable `main` branch after ensuring no code regressions.
