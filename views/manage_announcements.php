<?php
// views/manage_announcements.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../config/db.php';

$announcements = db_fetch_all("SELECT * FROM announcements WHERE course_id IS NULL ORDER BY created_at DESC");
?>

<div class="table-container" style="margin-bottom: 30px;">
    <h2>Create Platform-wide Announcement</h2>
    <form action="../controllers/announcement_controller.php" method="POST">
        <input type="hidden" name="action" value="create">
        <div style="margin-bottom: 10px;">
            <label>Title</label><br>
            <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label>Message Content</label><br>
            <textarea name="body" required style="width: 100%; padding: 10px; height: 100px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
        </div>
        <button type="submit" class="btn btn-approve">Post Announcement</button>
    </form>
</div>

<div class="table-container">
    <h2>Past Announcements</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($announcements as $ann): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($ann['title']); ?></strong></td>
                <td><?php echo nl2br(htmlspecialchars($ann['body'])); ?></td>
                <td><?php echo date('M d, Y H:i', strtotime($ann['created_at'])); ?></td>
                <td>
                    <a href="../controllers/announcement_controller.php?action=delete&id=<?php echo $ann['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($announcements)): ?>
            <tr><td colspan="4" style="text-align: center;">No announcements posted yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
