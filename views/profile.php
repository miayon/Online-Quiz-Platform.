<?php
// views/profile.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/UserModel.php';

$user = UserModel::getById($_SESSION['user_id']);

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<div class="table-container" style="max-width: 900px;">
    <h2>My Administrative Profile</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 30px;">Manage your personal information, profile picture, and security settings.</p>

    <?php if ($msg === 'updated'): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px;">Profile details updated successfully!</div>
    <?php elseif ($msg === 'password_changed'): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px;">Password changed successfully!</div>
    <?php elseif ($error === 'wrong_old_password'): ?>
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">Error: The old password you entered is incorrect.</div>
    <?php elseif ($error === 'mismatch'): ?>
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">Error: New passwords do not match.</div>
    <?php elseif ($error === 'too_short'): ?>
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">Error: New password must be at least 6 characters long.</div>
    <?php elseif ($error === 'failed'): ?>
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">An unexpected error occurred. Please try again.</div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 40px;">
        <!-- Left Sidebar: Profile Pic -->
        <div style="text-align: center;">
            <div style="width: 180px; height: 180px; border-radius: 50%; overflow: hidden; border: 4px solid #f0f0f0; margin: 0 auto 15px;">
                <?php if ($user['profile_pic']): ?>
                    <img src="../assets/uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="background: #eee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999;">
                        <span style="font-size: 50px;">👤</span>
                    </div>
                <?php endif; ?>
            </div>
            <p style="font-size: 14px; color: #888;">Admin ID: #<?php echo $user['id']; ?></p>
            <p><span class="status-badge" style="background: #e3f2fd; color: #1976d2;">Administrator</span></p>
        </div>

        <!-- Right Side: Forms -->
        <div>
            <!-- Info Form -->
            <form action="../controllers/profile_controller.php" method="POST" enctype="multipart/form-data" style="margin-bottom: 40px;">
                <input type="hidden" name="action" value="update_profile">
                
                <h3 style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Personal Details</h3>
                
                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required class="form-control">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email Address (Cannot be changed)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="form-control" style="background: #f9f9f9;">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Update Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control">
                </div>

                <button type="submit" class="btn btn-edit" style="width: 100%; padding: 12px; font-size: 16px;">Update Profile Information</button>
            </form>

            <!-- Password Form -->
            <form action="../controllers/profile_controller.php" method="POST" style="background: #fcfcfc; padding: 25px; border: 1px solid #eee; border-radius: 8px;">
                <input type="hidden" name="action" value="change_password">
                
                <h3 style="margin-bottom: 20px; color: var(--accent);">Security & Password</h3>
                
                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Current Password</label>
                    <input type="password" name="old_password" required class="form-control">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">New Password</label>
                    <input type="password" name="new_password" required class="form-control">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Confirm New Password</label>
                    <input type="password" name="confirm_password" required class="form-control">
                </div>

                <button type="submit" class="btn btn-reject" style="width: 100%; padding: 12px; font-size: 16px; background: #555;">Change Secure Password</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
