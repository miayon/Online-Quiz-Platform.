<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$materials = TAModel::getMaterials($courseId);

ta_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Upload Supplementary Material</h2>

        <form method="POST" action="../controllers/ta_controller.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_material">
            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

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

            <button class="btn btn-primary" type="submit">Upload</button>
        </form>
    </div>

    <div class="card">
        <h2>Materials</h2>

        <?php if (!$materials): ?>
            <p>No materials uploaded yet.</p>
        <?php endif; ?>

        <?php foreach ($materials as $material): ?>
            <details>
                <summary><?php echo h($material['title']); ?> <span class="badge badge-info"><?php echo h($material['material_type']); ?></span></summary>

                <p><strong>Path/Link:</strong> <?php echo h($material['file_path']); ?></p>
                <p><strong>Uploaded By:</strong> <?php echo h($material['uploaded_by_name']); ?></p>

                <?php if (intval($material['uploaded_by']) === $taId): ?>
                    <form method="POST" action="../controllers/ta_controller.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit_material">
                        <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                        <input type="hidden" name="material_id" value="<?php echo intval($material['id']); ?>">
                        <input type="hidden" name="old_file_path" value="<?php echo h($material['file_path']); ?>">

                        <div class="form-group">
                            <label>Edit Title</label>
                            <input type="text" name="title" value="<?php echo h($material['title']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Material Type</label>
                            <select name="material_type">
                                <option value="document" <?php echo $material['material_type'] === 'document' ? 'selected' : ''; ?>>Document</option>
                                <option value="video" <?php echo $material['material_type'] === 'video' ? 'selected' : ''; ?>>Video</option>
                                <option value="link" <?php echo $material['material_type'] === 'link' ? 'selected' : ''; ?>>External Link</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Replace File</label>
                            <input type="file" name="material_file">
                        </div>

                        <div class="form-group">
                            <label>External Link</label>
                            <input type="url" name="external_link" value="<?php echo $material['material_type'] === 'link' ? h($material['file_path']) : ''; ?>">
                        </div>

                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>

                    <form method="POST" action="../controllers/ta_controller.php" onsubmit="return confirm('Delete material?');">
                        <input type="hidden" name="action" value="delete_material">
                        <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                        <input type="hidden" name="material_id" value="<?php echo intval($material['id']); ?>">
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
