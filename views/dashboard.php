<?php
// views/dashboard.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/UserModel.php';

$stats = UserModel::getStats();

$active_courses = db_fetch_one("SELECT COUNT(*) as count FROM courses")['count'];
$quiz_attempts_today = db_fetch_one("SELECT COUNT(*) as count FROM attempts WHERE DATE(started_at) = CURDATE()")['count'];

// Fetch pending instructors
$pending_instructors_list = db_fetch_all("SELECT * FROM users WHERE role = 'instructor' AND is_active = 0 ORDER BY created_at DESC");
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value"><?php echo $stats['total_users']; ?></div>
        <div style="font-size: 13px; color: #888; margin-top: 10px; text-align: left; line-height: 1.6; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px;">
            👥 Students: <strong><?php echo $stats['total_students']; ?></strong><br>
            🎓 Instructors: <strong><?php echo $stats['total_instructors']; ?></strong><br>
            🛡️ TAs: <strong><?php echo $stats['total_tas']; ?></strong><br>
            🔑 Admins: <strong><?php echo $stats['total_admins']; ?></strong>
        </div>
    </div>
    <div class="stat-card">
        <h3>Active Courses</h3>
        <div class="value"><?php echo $active_courses; ?></div>
        <p>Currently running</p>
    </div>
    <div class="stat-card">
        <h3>Attempts Today</h3>
        <div class="value" style="color: var(--accent);"><?php echo $quiz_attempts_today; ?></div>
        <p>Quiz completions</p>
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

<?php if (!empty($pending_instructors_list)): ?>
<div class="table-container" style="border: 1px solid rgba(245, 124, 0, 0.4); background: rgba(245, 124, 0, 0.02); margin-bottom: 30px; border-radius: 12px; padding: 25px;">
    <h2 style="color: #f57c00; display: flex; align-items: center; gap: 10px; font-size: 20px; font-weight: 600;">
        <span>⏳ Pending Instructor Approvals</span>
        <span class="badge" style="background: #fff3e0; color: #f57c00; font-size: 14px; border-radius: 6px; padding: 4px 8px;"><?php echo count($pending_instructors_list); ?></span>
    </h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Review and approve or reject registration requests from new faculty members.</p>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Bio</th>
                <th>Date Requested</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pending_instructors_list as $inst): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($inst['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($inst['email']); ?></td>
                <td><?php echo htmlspecialchars($inst['department'] ?? 'N/A'); ?></td>
                <td><small><?php echo htmlspecialchars($inst['bio'] ?? 'N/A'); ?></small></td>
                <td><?php echo date('M d, Y H:i', strtotime($inst['created_at'])); ?></td>
                <td>
                    <a href="../controllers/user_controller.php?action=approve_instructor&id=<?php echo $inst['id']; ?>" class="btn btn-approve" style="background: #2e7d32; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 13px; display: inline-block;">Approve</a>
                    <a href="../controllers/user_controller.php?action=reject_instructor&id=<?php echo $inst['id']; ?>" class="btn btn-delete" style="background: #c62828; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 13px; display: inline-block; margin-left: 5px;" onclick="return confirm('Are you sure you want to reject this request?')">Reject</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

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
