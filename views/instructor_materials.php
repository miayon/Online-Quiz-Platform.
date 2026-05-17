<?php
$pageTitle = "Course Materials";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$materials = InstructorModel::getMaterials($courseId);

instructor_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Upload Material</h2>

        <form method="POST" action="../controllers/instructor_controller.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_material">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Material Type</label>
                <select name="material_type">
                    <option value="document">Document</option>
                    <option value="video">Video</option>
                    <option value="link">External Link</option>
                </select>
            </div>

            <div class="form-group">
                <label>Upload File</label>
                <input type="file" name="material_file">
            </div>

            <div class="form-group">
                <label>External Link</label>
                <input type="url" name="external_link" placeholder="https://example.com">
            </div>

            <button class="btn" type="submit">Upload</button>
        </form>
    </div>

    <div class="card">
        <h2>Uploaded Materials</h2>

        <?php if (!$materials): ?>
            <p>No materials found.</p>
        <?php endif; ?>

        <?php foreach ($materials as $material): ?>
            <details>
                <summary>
                    <?= h($material["title"]) ?>
                    <span class="badge"><?= h($material["material_type"]) ?></span>
                </summary>

                <p><strong>Path/Link:</strong> <?= h($material["file_path"]) ?></p>
                <p><strong>Uploaded By:</strong> <?= h($material["uploaded_by_name"]) ?></p>

                <?php if ((int)$material["uploaded_by"] === $instructorId): ?>
                    <form method="POST" action="../controllers/instructor_controller.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit_material">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">
                        <input type="hidden" name="material_id" value="<?= (int)$material["id"] ?>">
                        <input type="hidden" name="old_file_path" value="<?= h($material["file_path"]) ?>">

                        <div class="form-group">
                            <label>Edit Title</label>
                            <input type="text" name="title" value="<?= h($material["title"]) ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Material Type</label>
                            <select name="material_type">
                                <option value="document" <?= $material["material_type"] === "document" ? "selected" : "" ?>>Document</option>
                                <option value="video" <?= $material["material_type"] === "video" ? "selected" : "" ?>>Video</option>
                                <option value="link" <?= $material["material_type"] === "link" ? "selected" : "" ?>>External Link</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Replace File</label>
                            <input type="file" name="material_file">
                        </div>

                        <div class="form-group">
                            <label>External Link</label>
                            <input type="url" name="external_link" value="<?= $material["material_type"] === "link" ? h($material["file_path"]) : "" ?>">
                        </div>

                        <button class="btn" type="submit">Save</button>
                    </form>

                    <form method="POST" action="../controllers/instructor_controller.php" onsubmit="return confirm('Delete this material?');">
                        <input type="hidden" name="action" value="delete_material">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">
                        <input type="hidden" name="material_id" value="<?= (int)$material["id"] ?>">
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
