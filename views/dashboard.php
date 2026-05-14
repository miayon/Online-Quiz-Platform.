<?php
// views/dashboard.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/UserModel.php';

$stats = UserModel::getStats();

// Dummy data for "Work of other roles"
$active_courses = db_fetch_one("SELECT COUNT(*) as count FROM courses")['count'];
$quiz_attempts_today = db_fetch_one("SELECT COUNT(*) as count FROM attempts WHERE DATE(started_at) = CURDATE()")['count'];
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value"><?php echo $stats['total_users']; ?></div>
        <p>Across all roles</p>
    </div>
    <div class="stat-card">
        <h3>Active Courses</h3>
        <div class="value"><?php echo $active_courses; ?></div>
        <p>Currently running</p>
    </div>
    <div class="stat-card">
        <h3>Integrity Flags</h3>
        <div class="value" style="color: var(--danger);"><?php echo $stats['pending_integrity_flags']; ?></div>
        <p>Pending review</p>
    </div>
    <div class="stat-card">
        <h3>Pending Approvals</h3>
        <div class="value" style="color: var(--danger);"><?php echo $stats['pending_instructors']; ?></div>
        <p>Instructors waiting</p>
    </div>
</div>

<div class="table-container">
    <h2>Recent User Signups</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined At</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $users = array_slice(UserModel::getAll(), 0, 5); 
            foreach ($users as $user): 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo strtoupper($user['role']); ?></span></td>
                <td>
                    <?php if ($user['is_active']): ?>
                        <span style="color: var(--success);">Active</span>
                    <?php else: ?>
                        <span style="color: var(--danger);">Inactive</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 15px; text-align: right;">
        <a href="manage_users.php" style="color: var(--accent); text-decoration: none;">View All Users &rarr;</a>
    </div>
</div>

</body>
</html>
