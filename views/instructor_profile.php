<?php
$pageTitle = "Instructor Profile";
require_once __DIR__ . "/instructor_header.php";

$instructor = InstructorModel::getInstructor((int)$_SESSION["user_id"]);
?>

<div class="card" style="max-width:760px;">
    <h2>Professional Profile</h2>

    <form method="POST" action="../controllers/instructor_controller.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_profile">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= h($instructor["name"]) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?= h($instructor["email"]) ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= h($instructor["phone"]) ?>">
        </div>

        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" value="<?= h($instructor["program"]) ?>">
        </div>

        <div class="form-group">
            <label>Bio</label>
            <textarea name="bio" rows="5"><?= h($instructor["bio"] ?? "") ?></textarea>
        </div>

        <div class="form-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic">
        </div>

        <button class="btn" type="submit">Update Profile</button>
    </form>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
