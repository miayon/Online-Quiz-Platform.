<?php
$pageTitle = "Course Performance Report";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$report = InstructorModel::getCourseReport($instructorId, $courseId);
$quizRows = InstructorModel::getQuizReportRows($instructorId, $courseId);

$totalEnrolled = max(1, (int)$report["total_enrolled"]);
$dropoutRate = round(((int)$report["dropped_students"] / $totalEnrolled) * 100, 2);

instructor_course_tabs($courseId);
?>

<div class="grid">
    <div class="stat-card"><span>Enrolled Students</span><strong><?= h($report["total_enrolled"]) ?></strong></div>
    <div class="stat-card"><span>Dropped Students</span><strong><?= h($report["dropped_students"]) ?></strong></div>
    <div class="stat-card"><span>Drop-out Rate</span><strong><?= h($dropoutRate) ?>%</strong></div>
    <div class="stat-card"><span>Total Quizzes</span><strong><?= h($report["total_quizzes"]) ?></strong></div>
    <div class="stat-card"><span>Total Attempts</span><strong><?= h($report["total_attempts"]) ?></strong></div>
    <div class="stat-card"><span>Overall Average</span><strong><?= h($report["overall_average"]) ?></strong></div>
</div>

<div class="card">
    <h2>Quiz Completion and Average Scores</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Quiz</th>
                    <th>Attempted Students</th>
                    <th>Enrolled Students</th>
                    <th>Completion Rate</th>
                    <th>Average Score</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$quizRows): ?>
                    <tr><td colspan="5">No quiz report data found.</td></tr>
                <?php endif; ?>

                <?php foreach ($quizRows as $row): ?>
                    <?php
                    $enrolled = max(1, (int)$row["enrolled_students"]);
                    $completion = round(((int)$row["attempted_students"] / $enrolled) * 100, 2);
                    ?>
                    <tr>
                        <td><?= h($row["title"]) ?></td>
                        <td><?= h($row["attempted_students"]) ?></td>
                        <td><?= h($row["enrolled_students"]) ?></td>
                        <td><?= h($completion) ?>%</td>
                        <td><?= h($row["average_score"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
