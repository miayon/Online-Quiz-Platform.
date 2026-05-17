<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$quizzes = TAModel::getCourseQuizzes($courseId);
$questions = TAModel::getQuestions($courseId);

ta_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Add Question</h2>

        <form method="POST" action="../controllers/ta_controller.php">
            <input type="hidden" name="action" value="add_question">
            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

            <div class="form-group">
                <label>Select Quiz</label>
                <select name="quiz_id" required>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?php echo intval($quiz['id']); ?>"><?php echo h($quiz['title']); ?></option>
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

            <div class="form-group"><label>Option 1</label><input type="text" name="option1" required></div>
            <div class="form-group"><label>Option 2</label><input type="text" name="option2" required></div>
            <div class="form-group"><label>Option 3</label><input type="text" name="option3" required></div>
            <div class="form-group"><label>Option 4</label><input type="text" name="option4" required></div>

            <div class="form-group">
                <label>Correct Option</label>
                <select name="correct_option" required>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Add Question</button>
        </form>
    </div>

    <div class="card">
        <h2>Question Bank</h2>

        <?php if (!$questions): ?>
            <p>No questions found.</p>
        <?php endif; ?>

        <?php foreach ($questions as $question): ?>
            <?php $options = TAModel::getQuestionOptions($question['id']); ?>
            <details>
                <summary><?php echo h($question['quiz_title']); ?> — <?php echo h($question['question_text']); ?></summary>

                <form method="POST" action="../controllers/ta_controller.php" style="margin-top:15px;">
                    <input type="hidden" name="action" value="edit_question">
                    <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                    <input type="hidden" name="question_id" value="<?php echo intval($question['id']); ?>">

                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea name="question_text" rows="3"><?php echo h($question['question_text']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" name="marks" value="<?php echo h($question['marks']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Order Index</label>
                        <input type="number" name="order_index" value="<?php echo h($question['order_index']); ?>">
                    </div>

                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="form-group">
                            <label>Option <?php echo $i + 1; ?></label>
                            <input type="text" name="option<?php echo $i + 1; ?>" value="<?php echo h($options[$i]['option_text'] ?? ''); ?>">
                        </div>
                    <?php endfor; ?>

                    <div class="form-group">
                        <label>Correct Option</label>
                        <select name="correct_option">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <option value="<?php echo $i + 1; ?>" <?php echo !empty($options[$i]['is_correct']) ? 'selected' : ''; ?>>
                                    Option <?php echo $i + 1; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <button class="btn btn-primary" type="submit">Save</button>
                </form>

                <form method="POST" action="../controllers/ta_controller.php" onsubmit="return confirm('Delete this question?');">
                    <input type="hidden" name="action" value="delete_question">
                    <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                    <input type="hidden" name="question_id" value="<?php echo intval($question['id']); ?>">
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
