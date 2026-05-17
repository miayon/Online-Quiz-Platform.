<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$threshold = intval(TAModel::getSetting('ta_at_risk_threshold', 50));
$attempts = TAModel::getAttemptResults($courseId);

ta_course_tabs($courseId);
?>

<div class="table-container">
    <h2>Student Attempt Results</h2>

    <form method="POST" action="../controllers/ta_controller.php" style="max-width:300px;">
        <input type="hidden" name="action" value="update_threshold">
        <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

        <div class="form-group">
            <label>At-Risk Threshold</label>
            <input type="number" name="threshold" value="<?php echo h($threshold); ?>" min="0" max="100">
        </div>

        <button class="btn btn-primary" type="submit">Update Threshold</button>
    </form>

    <br>

    <button type="button" class="btn btn-warning" id="loadAtRiskBtn">Load At-Risk Students with AJAX</button>
    <div id="atRiskBox" class="ajax-box"></div>

    <br>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Quiz</th>
                <th>Score</th>
                <th>Completed</th>
                <th>Pass/Fail</th>
                <th>At-Risk</th>
                <th>Flag</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$attempts): ?>
                <tr><td colspan="7">No attempts found.</td></tr>
            <?php endif; ?>

            <?php foreach ($attempts as $attempt): ?>
                <?php
                $score = intval($attempt['score']);
                $passed = $score >= intval($attempt['pass_mark']);
                $atRisk = $score < $threshold;
                ?>
                <tr>
                    <td><?php echo h($attempt['student_name']); ?><br><small><?php echo h($attempt['student_email']); ?></small></td>
                    <td><?php echo h($attempt['quiz_title']); ?></td>
                    <td><?php echo h($score); ?> / <?php echo h($attempt['total_marks']); ?></td>
                    <td><?php echo h($attempt['completed_at'] ?? $attempt['started_at']); ?></td>
                    <td>
                        <?php if ($passed): ?>
                            <span class="badge badge-success">Pass</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Fail</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($atRisk): ?>
                            <span class="badge badge-danger">At-Risk</span>
                        <?php else: ?>
                            <span class="badge badge-success">Safe</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (intval($attempt['flagged']) === 1): ?>
                            <span class="badge badge-warning">Flagged</span>
                        <?php else: ?>
                            <form method="POST" action="../controllers/ta_controller.php">
                                <input type="hidden" name="action" value="flag_student">
                                <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                                <input type="hidden" name="attempt_id" value="<?php echo intval($attempt['attempt_id']); ?>">
                                <input type="hidden" name="student_id" value="<?php echo intval($attempt['student_id']); ?>">
                                <input type="hidden" name="reason" value="Score below threshold or needs instructor review">
                                <button class="btn btn-warning" type="submit">Flag</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../assets/js/ta_ajax.js"></script>
<script>
    setupAtRiskAjax(<?php echo intval($courseId); ?>, <?php echo intval($threshold); ?>);
</script>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
