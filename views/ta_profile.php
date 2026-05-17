<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$profile = TAModel::getProfile($taId);
?>

<div class="card" style="max-width: 700px;">
    <h2>Manage TA Profile</h2>

    <form method="POST" action="../controllers/ta_controller.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo h($profile['name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo h($profile['email']); ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo h($profile['phone']); ?>">
        </div>

        <div class="form-group">
            <label>Department / Program</label>
            <input type="text" name="program" value="<?php echo h($profile['program']); ?>">
        </div>

        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic">
        </div>

        <button class="btn btn-primary" type="submit">Update Profile</button>
    </form>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
