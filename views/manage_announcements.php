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
        <div class="form-group">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Title</label>
            <input type="text" name="title" class="form-control" required placeholder="Announcement Title">
        </div>
        <div class="form-group">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Message Content</label>
            <textarea name="body" class="form-control" required style="height: 120px;" placeholder="Write your announcement message here..."></textarea>
        </div>
        <button type="submit" class="btn btn-approve" style="width: 200px; padding: 12px;">Post Announcement</button>
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
                <td class="actions-cell">
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
