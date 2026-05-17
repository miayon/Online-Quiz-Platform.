<?php
// views/manage_users.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/UserModel.php';

$users = UserModel::getAll();
?>

<div class="table-container" style="margin-bottom: 30px;">
    <h2>Register New Teaching Assistant (TA)</h2>
    <form action="../controllers/user_controller.php" method="POST" class="flex-form">
        <input type="hidden" name="action" value="create_ta">
        <div>
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Name</label>
            <input type="text" name="name" class="form-control" required placeholder="Full Name">
        </div>
        <div>
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Email Address">
        </div>
        <div>
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Initial Password">
        </div>
        <div style="flex: 0 0 auto;">
            <button type="submit" class="btn btn-approve" style="width: 150px; padding: 10px;">Create TA</button>
        </div>
    </form>
</div>
<?php
// Fetch pending instructors
$pending_instructors_list = db_fetch_all("SELECT * FROM users WHERE role = 'instructor' AND is_active = 0 ORDER BY created_at DESC");
?>

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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>All Users</h2>
        <input type="text" id="userSearchInput" class="form-control" placeholder="Search by name, email or ID..." onkeyup="searchUsers()" style="max-width: 350px;">
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <select class="form-control" style="padding: 4px 8px; border-radius: 4px; font-size: 13px; background: white; border: 1px solid #ccc;" onchange="changeUserRole(<?php echo $user['id']; ?>, this.value)">
                        <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>STUDENT</option>
                        <option value="instructor" <?php echo $user['role'] == 'instructor' ? 'selected' : ''; ?>>INSTRUCTOR</option>
                        <option value="ta" <?php echo $user['role'] == 'ta' ? 'selected' : ''; ?>>TA</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>ADMIN</option>
                    </select>
                </td>
                <td>
                    <?php if ($user['is_active']): ?>
                        <span style="color: var(--success);">Active</span>
                    <?php else: ?>
                        <span style="color: var(--danger);">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="actions-cell">
                    <a href="../controllers/user_controller.php?action=toggle_status&id=<?php echo $user['id']; ?>" class="btn <?php echo $user['is_active'] ? 'btn-delete' : 'btn-approve'; ?>">
                        <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                    </a>
                    <a href="../controllers/user_controller.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function changeUserRole(userId, newRole) {
    if (confirm("Are you sure you want to change this user's role to " + newRole.toUpperCase() + "?")) {
        window.location.href = "../controllers/user_controller.php?action=change_role&id=" + userId + "&role=" + newRole;
    } else {
        window.location.reload();
    }
}
</script>
<script src="../assets/js/user_search.js"></script>
</body>
</html>
