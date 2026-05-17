<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$threshold = intval(TAModel::getSetting('ta_at_risk_threshold', 50));
$report = TAModel::getCourseReport($courseId, $threshold);

ta_course_tabs($courseId);
?>

<div class="card">
    <h2>Course Summary Report</h2>

    <div class="report-grid">
        <div class="stat-card"><h3>Total Students</h3><div class="value"><?php echo h($report['total_students']); ?></div></div>
        <div class="stat-card"><h3>Total Quizzes</h3><div class="value"><?php echo h($report['total_quizzes']); ?></div></div>
        <div class="stat-card"><h3>Total Attempts</h3><div class="value"><?php echo h($report['total_attempts']); ?></div></div>
        <div class="stat-card"><h3>Average Score</h3><div class="value"><?php echo h($report['average_score']); ?>%</div></div>
        <div class="stat-card"><h3>At-Risk Students</h3><div class="value"><?php echo h($report['at_risk_students']); ?></div></div>
        <div class="stat-card"><h3>Materials</h3><div class="value"><?php echo h($report['total_materials']); ?></div></div>
        <div class="stat-card"><h3>Q&A Questions</h3><div class="value"><?php echo h($report['total_qa']); ?></div></div>
        <div class="stat-card"><h3>Doubt Sessions</h3><div class="value"><?php echo h($report['total_sessions']); ?></div></div>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
