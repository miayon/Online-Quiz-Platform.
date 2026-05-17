<?php
$pageTitle = "Quiz Analytics";
require_once __DIR__ . "/instructor_header.php";

$instructorId = (int)$_SESSION["user_id"];
$courseId = (int)($_GET["course_id"] ?? 0);
$quizId = (int)($_GET["quiz_id"] ?? 0);

require_instructor_course($instructorId, $courseId);

$quizzes = InstructorModel::getQuizzes($instructorId, $courseId);

instructor_course_tabs($courseId);
?>

<div class="card">
    <h2>Quiz Analytics via AJAX</h2>

    <div class="form-group">
        <label>Select Quiz</label>
        <select id="quizSelect">
            <option value="">Select Quiz</option>
            <?php foreach ($quizzes as $quiz): ?>
                <option value="<?= (int)$quiz["id"] ?>" <?= $quizId === (int)$quiz["id"] ? "selected" : "" ?>>
                    <?= h($quiz["title"]) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn" type="button" onclick="loadQuizAnalytics(document.getElementById('quizSelect').value)">
        Load Analytics
    </button>
</div>

<div id="analyticsBox" class="card" style="display:none;"></div>

<script src="../assets/js/instructor_ajax.js"></script>

<?php if ($quizId > 0): ?>
<script>
    loadQuizAnalytics(<?= $quizId ?>);
</script>
<?php endif; ?>

<?php require_once __DIR__ . "/instructor_footer.php"; ?>
