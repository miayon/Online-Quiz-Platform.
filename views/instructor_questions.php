<?php
$pageTitle = "Question Bank";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);
$selectedQuizId = (int)($_GET["quiz_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$quizzes = InstructorModel::getQuizzes($instructorId, $courseId);
$questions = InstructorModel::getQuestions($instructorId, $courseId, $selectedQuizId);

instructor_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Add Question</h2>

        <?php if (!$quizzes): ?>
            <p class="notice">Create a quiz first.</p>
        <?php else: ?>
            <form method="POST" action="../controllers/instructor_controller.php">
                <input type="hidden" name="action" value="add_question">
                <input type="hidden" name="course_id" value="<?= $courseId ?>">

                <div class="form-group">
                    <label>Select Quiz</label>
                    <select name="quiz_id" required>
                        <?php foreach ($quizzes as $quiz): ?>
                            <option value="<?= (int)$quiz["id"] ?>" <?= $selectedQuizId === (int)$quiz["id"] ? "selected" : "" ?>>
                                <?= h($quiz["title"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Question Text</label>
                    <textarea name="question_text" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Marks</label>
                    <input type="number" name="marks" value="1" min="1" required>
                </div>

                <div class="form-group">
                    <label>Order Index</label>
                    <input type="number" name="order_index" value="1" min="1" required>
                </div>

                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="form-group">
                        <label>Option <?= $i ?></label>
                        <input type="text" name="option<?= $i ?>" required>
                    </div>
                <?php endfor; ?>

                <div class="form-group">
                    <label>Correct Option</label>
                    <select name="correct_option" required>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <option value="<?= $i ?>">Option <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button class="btn" type="submit">Add Question</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Course-Level Question Bank</h2>

        <?php if (!$questions): ?>
            <p>No questions found.</p>
        <?php endif; ?>

        <?php foreach ($questions as $question): ?>
            <?php $options = InstructorModel::getQuestionOptions((int)$question["id"]); ?>

            <details>
                <summary><?= h($question["quiz_title"]) ?> — <?= h($question["question_text"]) ?></summary>

                <form method="POST" action="../controllers/instructor_controller.php" style="margin-top:14px;">
                    <input type="hidden" name="action" value="edit_question">
                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                    <input type="hidden" name="question_id" value="<?= (int)$question["id"] ?>">

                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea name="question_text" rows="3"><?= h($question["question_text"]) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" name="marks" value="<?= h($question["marks"]) ?>" min="1">
                    </div>

                    <div class="form-group">
                        <label>Order Index</label>
                        <input type="number" name="order_index" value="<?= h($question["order_index"]) ?>" min="1">
                    </div>

                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="form-group">
                            <label>Option <?= $i + 1 ?></label>
                            <input type="text" name="option<?= $i + 1 ?>" value="<?= h($options[$i]["option_text"] ?? "") ?>">
                        </div>
                    <?php endfor; ?>

                    <div class="form-group">
                        <label>Correct Option</label>
                        <select name="correct_option">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <option value="<?= $i + 1 ?>" <?= !empty($options[$i]["is_correct"]) ? "selected" : "" ?>>
                                    Option <?= $i + 1 ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button class="btn" type="submit">Save Question</button>
                </form>

                <form method="POST" action="../controllers/instructor_controller.php" onsubmit="return confirm('Delete this question?');">
                    <input type="hidden" name="action" value="delete_question">
                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                    <input type="hidden" name="question_id" value="<?= (int)$question["id"] ?>">
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>

                <form method="POST" action="../controllers/instructor_controller.php">
                    <input type="hidden" name="action" value="reuse_question">
                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                    <input type="hidden" name="source_question_id" value="<?= (int)$question["id"] ?>">

                    <div class="form-group">
                        <label>Reuse this question in another quiz</label>
                        <select name="target_quiz_id" required>
                            <?php foreach ($quizzes as $quiz): ?>
                                <option value="<?= (int)$quiz["id"] ?>"><?= h($quiz["title"]) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-warning" type="submit">Reuse Question</button>
                </form>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
