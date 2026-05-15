<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">

        <div>

            <h1>
                <?= htmlspecialchars(
                    $course["title"]
                ) ?>
            </h1>

            <p class="muted">
                <?= htmlspecialchars(
                    $course["subject_name"]
                ) ?>
            </p>

        </div>

        <div>

            <span class="badge">
                <?= htmlspecialchars(
                    $course["enrollment_status"]
                ) ?>
            </span>

        </div>

    </div>

    <hr style="margin:20px 0;">

    <p>
        <?= nl2br(
            htmlspecialchars(
                $course["description"]
            )
        ) ?>
    </p>

    <br>

    <div class="grid-4">

        <div class="stat-card">
            <h4>Instructor</h4>

            <h2 style="font-size:18px;">
                <?= htmlspecialchars(
                    $course["instructor_name"]
                ) ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Enrollment Type</h4>

            <h2 style="font-size:18px;">
                <?= htmlspecialchars(
                    $course["enrollment_type"]
                ) ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Max Students</h4>

            <h2>
                <?= $course["max_students"] ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Status</h4>

            <h2 style="font-size:18px;">
                <?= htmlspecialchars(
                    $course["status"]
                ) ?>
            </h2>
        </div>

    </div>

    <br>

    <a
        href="drop_course.php?course_id=<?= $course["id"] ?>"
        class="btn btn-danger"
        onclick="
            return confirm(
                'Are you sure?'
            )
        "
    >
        Drop Course
    </a>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>