<?php
$pageTitle = "Enrollment Requests";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$requests = InstructorModel::getEnrollmentRequests($courseId);
$students = InstructorModel::getEnrolledStudents($courseId);

instructor_course_tabs($courseId);
?>

<div class="card">
    <h2>Pending Enrollment Requests</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Program</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$requests): ?>
                    <tr><td colspan="6">No pending requests.</td></tr>
                <?php endif; ?>

                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?= h($request["name"]) ?></td>
                        <td><?= h($request["email"]) ?></td>
                        <td><?= h($request["student_id"]) ?></td>
                        <td><?= h($request["program"]) ?></td>
                        <td><?= h($request["enrolled_at"]) ?></td>
                        <td>
                            <form method="POST" action="../controllers/instructor_controller.php" style="display:inline;">
                                <input type="hidden" name="action" value="handle_enrollment">
                                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                <input type="hidden" name="enrollment_id" value="<?= (int)$request["id"] ?>">
                                <input type="hidden" name="status" value="active">
                                <button class="btn btn-success" type="submit">Approve</button>
                            </form>

                            <form method="POST" action="../controllers/instructor_controller.php" style="display:inline;">
                                <input type="hidden" name="action" value="handle_enrollment">
                                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                <input type="hidden" name="enrollment_id" value="<?= (int)$request["id"] ?>">
                                <input type="hidden" name="status" value="dropped">
                                <button class="btn btn-danger" type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h2>All Course Enrollments</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Email</th>
                    <th>Student ID</th>
                    <th>Status</th>
                    <th>Enrolled At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= h($student["name"]) ?></td>
                        <td><?= h($student["email"]) ?></td>
                        <td><?= h($student["student_id"]) ?></td>
                        <td><span class="badge"><?= h($student["status"]) ?></span></td>
                        <td><?= h($student["enrolled_at"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
