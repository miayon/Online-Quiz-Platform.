<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courses = TAModel::getAssignedCourses($taId);

$totalCourses = count($courses);
$totalStudents = array_sum(array_column($courses, 'total_students'));
$totalQuizzes = array_sum(array_column($courses, 'total_quizzes'));
$threshold = TAModel::getSetting('ta_at_risk_threshold', 50);
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Assigned Courses</h3>
        <div class="value"><?php echo h($totalCourses); ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Students</h3>
        <div class="value"><?php echo h($totalStudents); ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Quizzes</h3>
        <div class="value"><?php echo h($totalQuizzes); ?></div>
    </div>
    <div class="stat-card">
        <h3>At-Risk Threshold</h3>
        <div class="value"><?php echo h($threshold); ?>%</div>
    </div>
</div>

<div class="table-container">
    <h2>Assigned Course Summary</h2>

    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>Subject</th>
                <th>Instructor</th>
                <th>Students</th>
                <th>Quizzes</th>
                <th>Average Score</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$courses): ?>
                <tr><td colspan="7">No assigned courses found.</td></tr>
            <?php endif; ?>

            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo h($course['title']); ?></td>
                    <td><?php echo h($course['subject_name']); ?></td>
                    <td><?php echo h($course['instructor_name']); ?></td>
                    <td><?php echo h($course['total_students']); ?></td>
                    <td><?php echo h($course['total_quizzes']); ?></td>
                    <td><?php echo h($course['average_score']); ?>%</td>
                    <td>
                        <a class="btn btn-primary" href="ta_course_detail.php?course_id=<?php echo intval($course['id']); ?>">Open</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
