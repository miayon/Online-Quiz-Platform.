<?php
require_once __DIR__ . "/init.php";

$course_id = $_GET["course_id"] ?? "";

$courses = $resourceModel->enrolledCourses($student_id);
$announcements = $resourceModel->getAnnouncements($student_id, $course_id);

include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <div>
            <h2>Announcements</h2>
            <p class="muted">
                Latest updates from your enrolled courses.
            </p>
        </div>

        <span class="badge">Notice Board</span>
    </div>

    <form method="GET">

        <div style="
            display:grid;
            grid-template-columns:1fr auto;
            gap:15px;
            align-items:end;
        ">

            <div>
                <label>Filter by Course</label>

                <select
                    name="course_id"
                    class="form-control"
                >
                    <option value="">
                        All Enrolled Courses
                    </option>

                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <option
                            value="<?= (int)$course["id"] ?>"
                            <?= ($course_id == $course["id"]) ? "selected" : "" ?>
                        >
                            <?= htmlspecialchars($course["title"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button class="btn" type="submit">
                Filter
            </button>

        </div>

    </form>

</div>

<?php if ($announcements->num_rows > 0): ?>

    <?php while ($announcement = $announcements->fetch_assoc()): ?>

        <div class="card">

            <div style="
                display:flex;
                justify-content:space-between;
                gap:20px;
                align-items:flex-start;
            ">

                <div>
                    <h2>
                        <?= htmlspecialchars($announcement["title"]) ?>
                    </h2>

                    <p class="muted">
                        <?= htmlspecialchars($announcement["course_title"]) ?>
                        •
                        Posted by
                        <?= htmlspecialchars($announcement["author_name"]) ?>
                        (<?= htmlspecialchars($announcement["author_role"]) ?>)
                    </p>
                </div>

                <span class="badge">
                    <?= htmlspecialchars($announcement["created_at"]) ?>
                </span>

            </div>

            <br>

            <p>
                <?= nl2br(htmlspecialchars($announcement["body"])) ?>
            </p>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="card">
        <p>No announcements found.</p>
    </div>

<?php endif; ?>

<?php
include __DIR__ . "/partials/footer.php";
?>