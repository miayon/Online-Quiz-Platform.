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
            <h2>Q&A Discussion Board</h2>

            <p class="muted">
                Ask questions and collaborate with classmates.
            </p>
        </div>

        <a
            href="ask_question.php"
            class="btn btn-success"
        >
            Ask Question
        </a>

    </div>

</div>

<?php while(
    $question =
    $questions->fetch_assoc()
): ?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">

        <div>

            <h3>
                <?= htmlspecialchars(
                    $question["title"]
                ) ?>
            </h3>

            <p class="muted">

                <?= htmlspecialchars(
                    $question["course_title"]
                ) ?>

                •

                <?= htmlspecialchars(
                    $question["student_name"]
                ) ?>

                •

                <?= $question["created_at"] ?>

            </p>

        </div>

        <div>

            <?php if (
                $question["is_resolved"]
            ): ?>

                <span class="badge">
                    Resolved
                </span>

            <?php else: ?>

                <span class="badge"
                style="
                    background:#fef3c7;
                    color:#92400e;
                ">
                    Open
                </span>

            <?php endif; ?>

        </div>

    </div>

    <br>

    <p>
        <?= nl2br(
            htmlspecialchars(
                substr(
                    $question["body"],
                    0,
                    200
                )
            )
        ) ?>
    </p>

    <br>

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">

        <div class="muted">

            <?= $question["total_answers"] ?>
            Answers

        </div>

        <a
            href="answer_view.php?id=<?= $question["id"] ?>"
            class="btn"
        >
            View Discussion
        </a>

    </div>

</div>

<?php endwhile; ?>

<?php
include __DIR__ . "/partials/footer.php";
?>