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
                <?= htmlspecialchars(
                    $question["title"]
                ) ?>
            </h2>

            <p class="muted">

                <?= htmlspecialchars(
                    $question["course_title"]
                ) ?>

                •

                <?= htmlspecialchars(
                    $question["student_name"]
                ) ?>

            </p>

        </div>

        <?php if (
            !$question["is_resolved"]
        ): ?>

            <a
                href="resolve_question.php?id=<?= $question["id"] ?>"
                class="btn btn-success"
            >
                Mark Resolved
            </a>

        <?php endif; ?>

    </div>

    <br>

    <div class="card"
    style="background:#f9fafb;">

        <?= nl2br(
            htmlspecialchars(
                $question["body"]
            )
        ) ?>

    </div>

</div>

<div class="card">

    <h2>Answers</h2>

    <br>

    <?php if (
        $answers->num_rows > 0
    ): ?>

        <?php while(
            $answer =
            $answers->fetch_assoc()
        ): ?>

            <div class="card"
            style="
                margin-bottom:15px;
                border-left:5px solid
                <?= $answer["is_endorsed"]
                    ? '#16a34a'
                    : '#2563eb' ?>;
            ">

                <div style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                ">

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                $answer["author_name"]
                            ) ?>
                        </strong>

                        <span class="muted">
                            (
                            <?= htmlspecialchars(
                                $answer["role"]
                            ) ?>
                            )
                        </span>

                    </div>

                    <?php if (
                        $answer["is_endorsed"]
                    ): ?>

                        <span class="badge"
                        style="
                            background:#dcfce7;
                            color:#166534;
                        ">
                            Endorsed
                        </span>

                    <?php endif; ?>

                </div>

                <br>

                <p>
                    <?= nl2br(
                        htmlspecialchars(
                            $answer["body"]
                        )
                    ) ?>
                </p>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="alert">
            No answers yet.
        </div>

    <?php endif; ?>

</div>

<div class="card">

    <h2>Add Your Answer</h2>

    <br>

    <form method="POST">

        <textarea
            name="body"
            class="form-control"
            rows="6"
            required
        ></textarea>

        <button
            type="submit"
            class="btn"
        >
            Submit Answer
        </button>

    </form>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>