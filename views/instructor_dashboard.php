<?php
$pageTitle = "Instructor Dashboard";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courses = InstructorModel::getCourses($instructorId);

$totalStudents = array_sum(array_column($courses, "enrolled_students"));
$totalQuizzes = array_sum(array_column($courses, "total_quizzes"));
$activeCourses = count(array_filter($courses, fn($c) => $c["status"] === "active"));
?>

<div class="grid">
    <div class="stat-card"><span>Total Courses</span><strong><?= count($courses) ?></strong></div>
    <div class="stat-card"><span>Active Courses</span><strong><?= h($activeCourses) ?></strong></div>
    <div class="stat-card"><span>Total Students</span><strong><?= h($totalStudents) ?></strong></div>
    <div class="stat-card"><span>Total Quizzes</span><strong><?= h($totalQuizzes) ?></strong></div>
</div>

<div class="card">
    <h2>My Courses</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Enrollment</th>
                    <th>Students</th>
                    <th>Quizzes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$courses): ?>
                    <tr><td colspan="7">No courses found.</td></tr>
                <?php endif; ?>

                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= h($course["title"]) ?></td>
                        <td><?= h($course["subject_name"]) ?></td>
                        <td><span class="badge"><?= h($course["status"]) ?></span></td>
                        <td><?= h($course["enrollment_type"]) ?></td>
                        <td><?= h($course["enrolled_students"]) ?></td>
                        <td><?= h($course["total_quizzes"]) ?></td>
                        <td><a class="btn" href="instructor_course_detail.php?course_id=<?= (int)$course["id"] ?>">Open</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
