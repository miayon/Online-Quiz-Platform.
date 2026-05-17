<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$announcements = TAModel::getAnnouncements($courseId);

ta_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Send Announcement</h2>

        <form method="POST" action="../controllers/ta_controller.php">
            <input type="hidden" name="action" value="send_announcement">
            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="body" rows="5" required></textarea>
            </div>

            <button class="btn btn-primary" type="submit">Post as From TA</button>
        </form>
    </div>

    <div class="card">
        <h2>Course Announcements</h2>

        <?php if (!$announcements): ?>
            <p>No announcements yet.</p>
        <?php endif; ?>

        <?php foreach ($announcements as $announcement): ?>
            <details open>
                <summary><?php echo h($announcement['title']); ?></summary>
                <p><?php echo h($announcement['body']); ?></p>
                <small>By <?php echo h($announcement['author_name']); ?> | <?php echo h($announcement['created_at']); ?></small>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
