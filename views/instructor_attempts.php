<?php
$pageTitle = "Quiz Attempts";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);
$quizId = (int)($_GET["quiz_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$quizzes = InstructorModel::getQuizzes($instructorId, $courseId);
$attempts = $quizId > 0 ? InstructorModel::getQuizAttempts($instructorId, $quizId) : [];

instructor_course_tabs($courseId);
?>

<div class="card">
    <h2>Select Quiz</h2>

    <form method="GET">
        <input type="hidden" name="course_id" value="<?= $courseId ?>">

        <div class="form-group">
            <label>Quiz</label>
            <select name="quiz_id" onchange="this.form.submit()">
                <option value="">Select Quiz</option>
                <?php foreach ($quizzes as $quiz): ?>
                    <option value="<?= (int)$quiz["id"] ?>" <?= $quizId === (int)$quiz["id"] ? "selected" : "" ?>>
                        <?= h($quiz["title"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<div class="card">
    <h2>Student Attempts</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Score</th>
                    <th>Duration</th>
                    <th>Pass/Fail</th>
                    <th>Started</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($quizId && !$attempts): ?>
                    <tr><td colspan="7">No attempts found for this quiz.</td></tr>
                <?php endif; ?>

                <?php if (!$quizId): ?>
                    <tr><td colspan="7">Please select a quiz.</td></tr>
                <?php endif; ?>

                <?php foreach ($attempts as $attempt): ?>
                    <?php $passed = (int)$attempt["score"] >= (int)$attempt["pass_mark"]; ?>
                    <tr>
                        <td><?= h($attempt["student_name"]) ?><br><small><?= h($attempt["student_email"]) ?></small></td>
                        <td><?= h($attempt["student_id"]) ?></td>
                        <td><?= h($attempt["score"]) ?> / <?= h($attempt["total_marks"]) ?></td>
                        <td><?= h($attempt["duration_minutes"] ?? 0) ?> minutes</td>
                        <td>
                            <span class="badge <?= $passed ? "badge-success" : "badge-danger" ?>">
                                <?= $passed ? "Pass" : "Fail" ?>
                            </span>
                        </td>
                        <td><?= h($attempt["started_at"]) ?></td>
                        <td><?= h($attempt["completed_at"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
