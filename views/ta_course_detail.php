<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);

if (!$course) {
    die("Course not found or not assigned to you.");
}

$students = TAModel::getCourseStudents($courseId);
$quizzes = TAModel::getCourseQuizzes($courseId);

ta_course_tabs($courseId);
?>

<div class="stats-grid">
    <div class="stat-card"><h3>Students</h3><div class="value"><?php echo count($students); ?></div></div>
    <div class="stat-card"><h3>Quizzes</h3><div class="value"><?php echo count($quizzes); ?></div></div>
    <div class="stat-card"><h3>Enrollment</h3><div class="value" style="font-size:22px;"><?php echo h($course['enrollment_type']); ?></div></div>
    <div class="stat-card"><h3>Status</h3><div class="value" style="font-size:22px;"><?php echo h($course['status']); ?></div></div>
</div>

<div class="table-container">
    <h2>Course Information</h2>
    <p><strong>Title:</strong> <?php echo h($course['title']); ?></p>
    <p><strong>Description:</strong> <?php echo h($course['description']); ?></p>
    <p><strong>Subject:</strong> <?php echo h($course['subject_name']); ?></p>
    <p><strong>Instructor:</strong> <?php echo h($course['instructor_name']); ?></p>
</div>

<br>

<div class="table-container">
    <h2>Enrolled Students</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Student ID</th>
                <th>Program</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$students): ?>
                <tr><td colspan="5">No students enrolled.</td></tr>
            <?php endif; ?>

            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo h($student['name']); ?></td>
                    <td><?php echo h($student['email']); ?></td>
                    <td><?php echo h($student['student_id']); ?></td>
                    <td><?php echo h($student['program']); ?></td>
                    <td><span class="badge badge-info"><?php echo h($student['status']); ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
