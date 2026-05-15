<?php
// views/manage_users.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/UserModel.php';

$users = UserModel::getAll();
?>

<div class="table-container" style="margin-bottom: 30px;">
    <h2>Register New Teaching Assistant (TA)</h2>
    <form action="../controllers/user_controller.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; align-items: end;">
        <input type="hidden" name="action" value="create_ta">
        <div>
            <label>Name</label><br>
            <input type="text" name="name" required style="width: 100%; padding: 8px;">
        </div>
        <div>
            <label>Email</label><br>
            <input type="email" name="email" required style="width: 100%; padding: 8px;">
        </div>
        <div>
            <label>Password</label><br>
            <input type="password" name="password" required style="width: 100%; padding: 8px;">
        </div>
        <button type="submit" class="btn btn-approve">Create TA</button>
    </form>
</div>

<div class="table-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>All Users</h2>
        <input type="text" id="userSearchInput" placeholder="Search by name, email or ID..." onkeyup="searchUsers()" style="padding: 10px; width: 300px; border: 1px solid #ddd; border-radius: 4px;">
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
                <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo strtoupper($user['role']); ?></span></td>
                <td>
                    <?php if ($user['is_active']): ?>
                        <span style="color: var(--success);">Active</span>
                    <?php else: ?>
                        <span style="color: var(--danger);">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
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

<script src="../assets/js/user_search.js"></script>
</body>
</html>
