<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$questions = TAModel::getQAQuestions($courseId);

ta_course_tabs($courseId);
?>

<div class="card">
    <h2>Course Q&A Board</h2>

    <?php if (!$questions): ?>
        <p>No Q&A questions yet.</p>
    <?php endif; ?>

    <?php foreach ($questions as $question): ?>
        <?php $answers = TAModel::getQAAnswers($question['id']); ?>

        <details open>
            <summary>
                <?php echo h($question['title']); ?>
                <?php if (intval($question['is_resolved']) === 1): ?>
                    <span class="badge badge-success">Resolved</span>
                <?php endif; ?>
            </summary>

            <p><?php echo h($question['body']); ?></p>
            <small>Asked by <?php echo h($question['student_name']); ?> | <?php echo h($question['created_at']); ?></small>

            <?php if (intval($question['is_resolved']) !== 1): ?>
                <form method="POST" action="../controllers/ta_controller.php">
                    <input type="hidden" name="action" value="resolve_question">
                    <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                    <input type="hidden" name="qa_question_id" value="<?php echo intval($question['id']); ?>">
                    <button class="btn btn-success" type="submit">Mark Resolved</button>
                </form>
            <?php endif; ?>

            <?php foreach ($answers as $answer): ?>
                <div class="qa-answer">
                    <p><?php echo h($answer['body']); ?></p>
                    <small>Answered by <?php echo h($answer['author_name']); ?> | <?php echo h($answer['created_at']); ?></small>

                    <?php if (intval($answer['is_endorsed']) === 1): ?>
                        <span class="badge badge-success">Endorsed</span>
                    <?php else: ?>
                        <form method="POST" action="../controllers/ta_controller.php">
                            <input type="hidden" name="action" value="endorse_answer">
                            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                            <input type="hidden" name="answer_id" value="<?php echo intval($answer['id']); ?>">
                            <button class="btn btn-success" type="submit">Endorse</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <form method="POST" action="../controllers/ta_controller.php">
                <input type="hidden" name="action" value="answer_question">
                <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                <input type="hidden" name="qa_question_id" value="<?php echo intval($question['id']); ?>">

                <div class="form-group">
                    <label>Reply</label>
                    <textarea name="body" rows="3" required></textarea>
                </div>

                <button class="btn btn-primary" type="submit">Post Reply</button>
            </form>
        </details>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
