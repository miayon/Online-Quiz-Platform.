<?php
$pageTitle = "Course Details";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$course = InstructorModel::getCourse($instructorId, $courseId);
$subjects = InstructorModel::getSubjects();
$students = InstructorModel::getEnrolledStudents($courseId);

instructor_course_tabs($courseId);
?>

<div class="grid">
    <div class="stat-card"><span>Students</span><strong><?= count($students) ?></strong></div>
    <div class="stat-card"><span>Status</span><strong style="font-size:22px;"><?= h($course["status"]) ?></strong></div>
    <div class="stat-card"><span>Enrollment</span><strong style="font-size:22px;"><?= h($course["enrollment_type"]) ?></strong></div>
    <div class="stat-card"><span>Max Students</span><strong><?= h($course["max_students"]) ?></strong></div>
</div>

<div class="grid-2">
    <div class="card">
        <h2>Edit Course</h2>

        <form method="POST" action="../controllers/instructor_controller.php">
            <input type="hidden" name="action" value="update_course">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <div class="form-group">
                <label>Course Title</label>
                <input type="text" name="title" value="<?= h($course["title"]) ?>" required>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" required>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= (int)$subject["id"] ?>" <?= (int)$subject["id"] === (int)$course["subject_id"] ? "selected" : "" ?>>
                            <?= h($subject["name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"><?= h($course["description"]) ?></textarea>
            </div>

            <div class="form-group">
                <label>Enrollment Type</label>
                <select name="enrollment_type">
                    <option value="open" <?= $course["enrollment_type"] === "open" ? "selected" : "" ?>>Open</option>
                    <option value="approval" <?= $course["enrollment_type"] === "approval" ? "selected" : "" ?>>Approval Required</option>
                </select>
            </div>

            <div class="form-group">
                <label>Maximum Students</label>
                <input type="number" name="max_students" value="<?= h($course["max_students"]) ?>" min="1">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft" <?= $course["status"] === "draft" ? "selected" : "" ?>>Draft</option>
                    <option value="active" <?= $course["status"] === "active" ? "selected" : "" ?>>Active</option>
                    <option value="archived" <?= $course["status"] === "archived" ? "selected" : "" ?>>Archived</option>
                </select>
            </div>

            <button class="btn" type="submit">Save Changes</button>
        </form>

        <form method="POST" action="../controllers/instructor_controller.php" onsubmit="return confirm('Archive this course?');">
            <input type="hidden" name="action" value="archive_course">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">
            <button class="btn btn-danger" type="submit">Archive Course</button>
        </form>
    </div>

    <div class="card">
        <h2>Enrolled Students</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Program</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$students): ?>
                        <tr><td colspan="5">No students found.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= h($student["name"]) ?></td>
                            <td><?= h($student["email"]) ?></td>
                            <td><?= h($student["student_id"]) ?></td>
                            <td><?= h($student["program"]) ?></td>
                            <td><span class="badge"><?= h($student["status"]) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
