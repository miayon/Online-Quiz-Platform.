<?php
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
            <h2>Browse Courses</h2>
            <p class="muted">
                Explore available courses.
            </p>
        </div>

    </div>

    <form method="GET">

        <div style="
            display:grid;
            grid-template-columns:2fr 1fr auto;
            gap:15px;
            margin-bottom:20px;
        ">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search courses..."
                value="<?= htmlspecialchars($search) ?>"
            >

            <select
                name="subject_id"
                class="form-control"
            >

                <option value="">
                    All Subjects
                </option>

                <?php while(
                    $subject =
                    $subjects->fetch_assoc()
                ): ?>

                    <option
                        value="<?= $subject["id"] ?>"

                        <?= (
                            $subject_id ==
                            $subject["id"]
                        ) ? "selected" : "" ?>
                    >

                        <?= htmlspecialchars(
                            $subject["name"]
                        ) ?>

                    </option>

                <?php endwhile; ?>

            </select>

            <button
                type="submit"
                class="btn"
            >
                Search
            </button>

        </div>

    </form>

    <table>

        <tr>
            <th>Course</th>
            <th>Subject</th>
            <th>Instructor</th>
            <th>Students</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if (
            $courses->num_rows > 0
        ): ?>

            <?php while(
                $course =
                $courses->fetch_assoc()
            ): ?>

                <tr>

                    <td>

                        <strong>
                            <?= htmlspecialchars(
                                $course["title"]
                            ) ?>
                        </strong>

                        <br>

                        <small class="muted">
                            <?= htmlspecialchars(
                                substr(
                                    $course["description"],
                                    0,
                                    80
                                )
                            ) ?>
                        </small>

                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $course["subject_name"]
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $course["instructor_name"]
                        ) ?>
                    </td>

                    <td>
                        <?= $course["enrolled_count"] ?>
                        /
                        <?= $course["max_students"] ?>
                    </td>

                    <td>

                        <?php if (
                            !empty(
                                $course["my_status"]
                            )
                        ): ?>

                            <span class="badge">
                                <?= htmlspecialchars(
                                    $course["my_status"]
                                ) ?>
                            </span>

                        <?php else: ?>

                            <span class="muted">
                                Not Enrolled
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <a
                            href="course_details.php?id=<?= $course["id"] ?>"
                            class="btn"
                        >
                            Details
                        </a>

                        <?php if (
                            empty(
                                $course["my_status"]
                            )
                        ): ?>

                            <a
                                href="enroll.php?course_id=<?= $course["id"] ?>"
                                class="btn btn-success"
                            >
                                Enroll
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="6">
                    No course found.
                </td>

            </tr>

        <?php endif; ?>

    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>