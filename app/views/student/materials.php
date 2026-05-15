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
            <h2>Course Materials</h2>
            <p class="muted">
                Access documents, links, videos and study resources.
            </p>
        </div>

        <span class="badge">Resources</span>
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

<div class="card">

    <h2>Available Materials</h2>

    <br>

    <table>
        <tr>
            <th>Title</th>
            <th>Course</th>
            <th>Type</th>
            <th>Uploaded By</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php if ($materials->num_rows > 0): ?>

            <?php while ($material = $materials->fetch_assoc()): ?>

                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars($material["title"]) ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($material["course_title"]) ?>
                    </td>

                    <td>
                        <span class="badge">
                            <?= htmlspecialchars($material["material_type"]) ?>
                        </span>
                    </td>

                    <td>
                        <?= htmlspecialchars($material["uploaded_by_name"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($material["created_at"]) ?>
                    </td>

                    <td>
                        <?php if ($material["material_type"] === "link" || str_starts_with($material["file_path"], "http")): ?>

                            <a
                                class="btn"
                                href="<?= htmlspecialchars($material["file_path"]) ?>"
                                target="_blank"
                            >
                                Open Link
                            </a>

                        <?php else: ?>

                            <a
                                class="btn"
                                href="../uploads/<?= htmlspecialchars($material["file_path"]) ?>"
                                target="_blank"
                                download
                            >
                                Download
                            </a>

                        <?php endif; ?>
                    </td>
                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">
                    No materials found.
                </td>
            </tr>

        <?php endif; ?>
    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>