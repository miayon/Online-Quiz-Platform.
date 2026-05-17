<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">

        <div>
            <h2>
                <?= htmlspecialchars($quiz["title"]) ?>
            </h2>

            <p class="muted">
                Complete all questions before the timer ends.
            </p>
        </div>

        <div class="badge">
            Time Left:
            <span id="timer"></span>
        </div>

    </div>

    <br>

    <form
        method="POST"
        action="submit_quiz.php"
        id="quizForm"
    >

        <input
            type="hidden"
            name="quiz_id"
            value="<?= (int)$quiz["id"] ?>"
        >

        <?php foreach ($questions as $question): ?>

            <div class="card" style="margin-bottom:20px;">

                <h3>
                    <?= htmlspecialchars($question["question_text"]) ?>
                </h3>

                <p class="muted">
                    Marks:
                    <?= (int)$question["marks"] ?>
                </p>

                <br>

                <?php foreach ($question["options"] as $option): ?>

                    <label style="
                        display:block;
                        margin-bottom:12px;
                        padding:12px;
                        background:#f8fafc;
                        border-radius:10px;
                        cursor:pointer;
                    ">

                        <input
                            type="radio"
                            name="answers[<?= (int)$question["id"] ?>]"
                            value="<?= (int)$option["id"] ?>"
                        >

                        <?= htmlspecialchars($option["option_text"]) ?>

                    </label>

                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>

        <button
            type="submit"
            class="btn btn-success"
        >
            Submit Quiz
        </button>

    </form>

</div>

<script>
let totalSeconds = <?= (int)$quiz["time_limit_minutes"] ?> * 60;

function updateTimer() {
    let minutes = Math.floor(totalSeconds / 60);
    let seconds = totalSeconds % 60;

    document.getElementById("timer").innerHTML =
        minutes + ":" + String(seconds).padStart(2, "0");

    if (totalSeconds <= 0) {
        document.getElementById("quizForm").submit();
    }

    totalSeconds--;
}

setInterval(updateTimer, 1000);
updateTimer();
</script>

<?php
include __DIR__ . "/partials/footer.php";
?>
