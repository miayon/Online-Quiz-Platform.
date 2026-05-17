<?php
$pageTitle = "Quizzes";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$quizzes = InstructorModel::getQuizzes($instructorId, $courseId);

instructor_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Create Quiz</h2>

        <form method="POST" action="../controllers/instructor_controller.php">
            <input type="hidden" name="action" value="create_quiz">
            <input type="hidden" name="course_id" value="<?= $courseId ?>">

            <div class="form-group">
                <label>Quiz Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Time Limit Minutes</label>
                <input type="number" name="time_limit_minutes" value="20" min="1" required>
            </div>

            <div class="form-group">
                <label>Total Marks</label>
                <input type="number" name="total_marks" value="10" min="1" required>
            </div>

            <div class="form-group">
                <label>Pass Mark</label>
                <input type="number" name="pass_mark" value="5" min="0" required>
            </div>

            <div class="form-group">
                <label>Quiz Type</label>
                <select name="quiz_type">
                    <option value="graded">Graded</option>
                    <option value="practice">Practice</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="form-group">
                <label>Available From</label>
                <input type="datetime-local" name="available_from">
            </div>

            <div class="form-group">
                <label>Available Until</label>
                <input type="datetime-local" name="available_until">
            </div>

            <button class="btn" type="submit">Create Quiz</button>
        </form>
    </div>

    <div class="card">
        <h2>All Quizzes</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Attempts</th>
                        <th>Average</th>
                        <th>Publish</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$quizzes): ?>
                        <tr><td colspan="7">No quizzes found.</td></tr>
                    <?php endif; ?>

                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?= h($quiz["title"]) ?></td>
                            <td><span class="badge"><?= h($quiz["quiz_type"]) ?></span></td>
                            <td><span class="badge badge-warning"><?= h($quiz["status"]) ?></span></td>
                            <td><?= h($quiz["attempt_count"]) ?></td>
                            <td><?= h($quiz["average_score"]) ?></td>
                            <td>
                                <?php $newStatus = $quiz["status"] === "published" ? "draft" : "published"; ?>
                                <form method="POST" action="../controllers/instructor_controller.php">
                                    <input type="hidden" name="action" value="toggle_quiz_status">
                                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                    <input type="hidden" name="quiz_id" value="<?= (int)$quiz["id"] ?>">
                                    <input type="hidden" name="status" value="<?= h($newStatus) ?>">
                                    <button class="btn btn-warning" type="submit">
                                        <?= $quiz["status"] === "published" ? "Unpublish" : "Publish" ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a class="btn" href="instructor_questions.php?course_id=<?= $courseId ?>&quiz_id=<?= (int)$quiz["id"] ?>">Questions</a>
                                <a class="btn btn-gray" href="instructor_attempts.php?course_id=<?= $courseId ?>&quiz_id=<?= (int)$quiz["id"] ?>">Attempts</a>
                                <a class="btn btn-success" href="instructor_analytics.php?course_id=<?= $courseId ?>&quiz_id=<?= (int)$quiz["id"] ?>">Analytics</a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7">
                                <details>
                                    <summary>Edit <?= h($quiz["title"]) ?></summary>
                                    <form method="POST" action="../controllers/instructor_controller.php">
                                        <input type="hidden" name="action" value="update_quiz">
                                        <input type="hidden" name="course_id" value="<?= $courseId ?>">
                                        <input type="hidden" name="quiz_id" value="<?= (int)$quiz["id"] ?>">

                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" value="<?= h($quiz["title"]) ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" rows="3"><?= h($quiz["description"]) ?></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Time Limit</label>
                                            <input type="number" name="time_limit_minutes" value="<?= h($quiz["time_limit_minutes"]) ?>" min="1">
                                        </div>

                                        <div class="form-group">
                                            <label>Total Marks</label>
                                            <input type="number" name="total_marks" value="<?= h($quiz["total_marks"]) ?>" min="1">
                                        </div>

                                        <div class="form-group">
                                            <label>Pass Mark</label>
                                            <input type="number" name="pass_mark" value="<?= h($quiz["pass_mark"]) ?>" min="0">
                                        </div>

                                        <div class="form-group">
                                            <label>Quiz Type</label>
                                            <select name="quiz_type">
                                                <option value="graded" <?= $quiz["quiz_type"] === "graded" ? "selected" : "" ?>>Graded</option>
                                                <option value="practice" <?= $quiz["quiz_type"] === "practice" ? "selected" : "" ?>>Practice</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status">
                                                <option value="draft" <?= $quiz["status"] === "draft" ? "selected" : "" ?>>Draft</option>
                                                <option value="published" <?= $quiz["status"] === "published" ? "selected" : "" ?>>Published</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Available From</label>
                                            <input type="datetime-local" name="available_from" value="<?= $quiz["available_from"] ? date('Y-m-d\TH:i', strtotime($quiz["available_from"])) : "" ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Available Until</label>
                                            <input type="datetime-local" name="available_until" value="<?= $quiz["available_until"] ? date('Y-m-d\TH:i', strtotime($quiz["available_until"])) : "" ?>">
                                        </div>

                                        <button class="btn" type="submit">Save Quiz</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
