<?php
$pageTitle = "Announcements";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$announcements = InstructorModel::getAnnouncements($courseId);

instructor_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Post Announcement</h2>

        <form method="POST" action="../controllers/instructor_controller.php">
            <input type="hidden" name="action" value="post_announcement">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Body</label>
                <textarea name="body" rows="5" required></textarea>
            </div>

            <button class="btn" type="submit">Post Announcement</button>
        </form>
    </div>

    <div class="card">
        <h2>Past Announcements</h2>

        <?php if (!$announcements): ?>
            <p>No announcements found.</p>
        <?php endif; ?>

        <?php foreach ($announcements as $announcement): ?>
            <details open>
                <summary><?= h($announcement["title"]) ?></summary>
                <p><?= h($announcement["body"]) ?></p>
                <small>By <?= h($announcement["author_name"]) ?> | <?= h($announcement["created_at"]) ?></small>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
