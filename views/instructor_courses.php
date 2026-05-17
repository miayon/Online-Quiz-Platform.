<?php
$pageTitle = "Courses";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courses = InstructorModel::getCourses($instructorId);
$subjects = InstructorModel::getSubjects();
?>

<div class="grid-2">
    <div class="card">
        <h2>Create New Course</h2>

        <form method="POST" action="../controllers/instructor_controller.php">
            <input type="hidden" name="action" value="create_course">

            <div class="form-group">
                <label>Course Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Subject</label>
                <select name="subject_id" required>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= (int)$subject["id"] ?>"><?= h($subject["name"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Enrollment Type</label>
                <select name="enrollment_type">
                    <option value="open">Open</option>
                    <option value="approval">Approval Required</option>
                </select>
            </div>

            <div class="form-group">
                <label>Maximum Students</label>
                <input type="number" name="max_students" value="50" min="1" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft">Save as Draft</option>
                    <option value="active">Publish / Active</option>
                </select>
            </div>

            <button class="btn" type="submit">Create Course</button>
        </form>
    </div>

    <div class="card">
        <h2>My Courses</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Students</th>
                        <th>Quizzes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$courses): ?>
                        <tr><td colspan="6">No courses found.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= h($course["title"]) ?></td>
                            <td><?= h($course["subject_name"]) ?></td>
                            <td><span class="badge"><?= h($course["status"]) ?></span></td>
                            <td><?= h($course["enrolled_students"]) ?></td>
                            <td><?= h($course["total_quizzes"]) ?></td>
                            <td>
                                <a class="btn" href="instructor_course_detail.php?course_id=<?= (int)$course["id"] ?>">Manage</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
