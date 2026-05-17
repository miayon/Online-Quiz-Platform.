<?php
$pageTitle = "Assign Teaching Assistant";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$tas = InstructorModel::getTAAccounts();
$assigned = InstructorModel::getAssignedTAs($courseId);

instructor_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Assign TA</h2>

        <form method="POST" action="../controllers/instructor_controller.php">
            <input type="hidden" name="action" value="assign_ta">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <div class="form-group">
                <label>Select TA</label>
                <select name="ta_id" required>
                    <?php foreach ($tas as $ta): ?>
                        <option value="<?= (int)$ta["id"] ?>">
                            <?= h($ta["name"]) ?> — <?= h($ta["email"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn" type="submit">Assign TA</button>
        </form>
    </div>

    <div class="card">
        <h2>Assigned TAs</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Assigned At</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$assigned): ?>
                        <tr><td colspan="5">No TA assigned yet.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($assigned as $ta): ?>
                        <tr>
                            <td><?= h($ta["name"]) ?></td>
                            <td><?= h($ta["email"]) ?></td>
                            <td><?= h($ta["program"]) ?></td>
                            <td><?= h($ta["assigned_at"]) ?></td>
                            <td>
                                <form method="POST" action="../controllers/instructor_controller.php">
                                    <input type="hidden" name="action" value="remove_ta">
                                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                    <input type="hidden" name="ta_id" value="<?= (int)$ta["ta_id"] ?>">
                                    <button class="btn btn-danger" type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
