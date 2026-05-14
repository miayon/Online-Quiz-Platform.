<?php
// views/audit_logs.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../config/db.php';

$logs = db_fetch_all("SELECT a.*, u.name as user_name FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 100");
?>

<div class="table-container">
    <h2>System Audit Logs</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Showing last 100 significant admin actions.</p>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Admin User</th>
                <th>Action</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo date('M d, Y H:i:s', strtotime($log['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($log['user_name'] ?? 'System/Guest'); ?></td>
                <td><strong style="color: var(--accent);"><?php echo htmlspecialchars($log['action']); ?></strong></td>
                <td><small><?php echo htmlspecialchars($log['details']); ?></small></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($logs)): ?>
            <tr><td colspan="4" style="text-align: center;">No logs found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
