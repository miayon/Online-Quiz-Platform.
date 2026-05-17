<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$quizzes = TAModel::getCourseQuizzes($courseId);

ta_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Create Practice Quiz</h2>
        <p class="notice">TA-created practice quizzes will stay pending until instructor approval.</p>

        <form method="POST" action="../controllers/ta_controller.php">
            <input type="hidden" name="action" value="create_quiz">
            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

            <div class="form-group">
                <label>Quiz Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Time Limit Minutes</label>
                <input type="number" name="time_limit_minutes" value="20" min="1" required>
            </div>

            <div class="form-group">
                <label>Total Marks</label>
                <input type="number" name="total_marks" value="10" min="1" required>
            </div>

            <div class="form-group">
                <label>Pass Mark</label>
                <input type="number" name="pass_mark" value="5" min="0" required>
            </div>

            <div class="form-group">
                <label>Available From</label>
                <input type="datetime-local" name="available_from">
            </div>

            <div class="form-group">
                <label>Available Until</label>
                <input type="datetime-local" name="available_until">
            </div>

            <button class="btn btn-primary" type="submit">Create Practice Quiz</button>
        </form>
    </div>

    <div class="table-container">
        <h2>All Quizzes</h2>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Marks</th>
                    <th>Pass</th>
                    <th>Creator</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$quizzes): ?>
                    <tr><td colspan="6">No quizzes found.</td></tr>
                <?php endif; ?>

                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo h($quiz['title']); ?></td>
                        <td><span class="badge badge-info"><?php echo h($quiz['quiz_type']); ?></span></td>
                        <td><span class="badge badge-warning"><?php echo h($quiz['status']); ?></span></td>
                        <td><?php echo h($quiz['total_marks']); ?></td>
                        <td><?php echo h($quiz['pass_mark']); ?></td>
                        <td><?php echo h($quiz['creator_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
