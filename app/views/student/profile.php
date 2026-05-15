<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <h2>My Profile</h2>

    <br>

    <?php if (!empty($message)): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <?php if (!empty($error)): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div style="display:flex; gap:40px;">

        <div>

            <?php if (!empty($user["profile_pic"])): ?>

                <img
                    src="../uploads/<?= htmlspecialchars($user["profile_pic"]) ?>"
                    width="140"
                    height="140"
                    style="
                        border-radius:50%;
                        object-fit:cover;
                        border:4px solid #2563eb;
                    "
                >

            <?php else: ?>

                <img
                    src="https://via.placeholder.com/140"
                    width="140"
                    height="140"
                    style="
                        border-radius:50%;
                        object-fit:cover;
                    "
                >

            <?php endif; ?>

        </div>

        <div style="flex:1;">

            <form
                method="POST"
                enctype="multipart/form-data"
            >

                <label>Full Name</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($user["name"]) ?>"
                    required
                >

                <label>Email Address</label>

                <input
                    type="email"
                    class="form-control"
                    value="<?= htmlspecialchars($user["email"]) ?>"
                    disabled
                >

                <label>Phone Number</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?= htmlspecialchars($user["phone"]) ?>"
                >

                <label>Program</label>

                <input
                    type="text"
                    name="program"
                    class="form-control"
                    value="<?= htmlspecialchars($user["program"]) ?>"
                >

                <label>Profile Picture</label>

                <input
                    type="file"
                    name="profile_pic"
                    class="form-control"
                >

                <button
                    type="submit"
                    name="update_profile"
                    class="btn"
                >
                    Update Profile
                </button>

            </form>

        </div>

    </div>

</div>

<div class="card">

    <h2>Change Password</h2>

    <br>

    <form method="POST">

        <label>Old Password</label>

        <input
            type="password"
            name="old_password"
            class="form-control"
            required
        >

        <label>New Password</label>

        <input
            type="password"
            name="new_password"
            class="form-control"
            required
        >

        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            class="form-control"
            required
        >

        <button
            type="submit"
            name="change_password"
            class="btn btn-success"
        >
            Change Password
        </button>

    </form>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>