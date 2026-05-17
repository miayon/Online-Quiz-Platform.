<?php
$pageTitle = "Q&A Board";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$questions = InstructorModel::getQAQuestions($courseId);

instructor_course_tabs($courseId);
?>

<div class="card">
    <h2>Course Q&A Board</h2>

    <?php if (!$questions): ?>
        <p>No Q&A questions found.</p>
    <?php endif; ?>

    <?php foreach ($questions as $question): ?>
        <?php $answers = InstructorModel::getQAAnswers((int)$question["id"]); ?>

        <details open>
            <summary>
                <?= h($question["title"]) ?>
                <?php if ((int)$question["is_resolved"] === 1): ?>
                    <span class="badge badge-success">Resolved</span>
                <?php endif; ?>
            </summary>

            <p><?= h($question["body"]) ?></p>
            <small>Asked by <?= h($question["student_name"]) ?> | <?= h($question["created_at"]) ?></small>

            <?php if ((int)$question["is_resolved"] !== 1): ?>
                <form method="POST" action="../controllers/instructor_controller.php">
                    <input type="hidden" name="action" value="resolve_qa">
                    <input type="hidden" name="course_id" value="<?= $courseId ?>">
                    <input type="hidden" name="qa_question_id" value="<?= (int)$question["id"] ?>">
                    <button class="btn btn-success" type="submit">Mark Resolved</button>
                </form>
            <?php endif; ?>

            <?php foreach ($answers as $answer): ?>
                <div class="qa-answer">
                    <p><?= h($answer["body"]) ?></p>
                    <small>Answered by <?= h($answer["author_name"]) ?> | <?= h($answer["created_at"]) ?></small><br>

                    <?php if ((int)$answer["is_endorsed"] === 1): ?>
                        <span class="badge badge-success">Endorsed</span>
                    <?php else: ?>
                        <form method="POST" action="../controllers/instructor_controller.php">
                            <input type="hidden" name="action" value="endorse_answer">
                            <input type="hidden" name="course_id" value="<?= $courseId ?>">
                            <input type="hidden" name="answer_id" value="<?= (int)$answer["id"] ?>">
                            <button class="btn btn-success" type="submit">Endorse</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <form method="POST" action="../controllers/instructor_controller.php">
                <input type="hidden" name="action" value="answer_qa">
                <input type="hidden" name="course_id" value="<?= $courseId ?>">
                <input type="hidden" name="qa_question_id" value="<?= (int)$question["id"] ?>">

                <div class="form-group">
                    <label>Reply</label>
                    <textarea name="body" rows="3" required></textarea>
                </div>

                <button class="btn" type="submit">Post Reply</button>
            </form>
        </details>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
