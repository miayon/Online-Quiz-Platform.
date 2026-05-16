<?php
require_once __DIR__ . "/init.php";

$quizzes = $quizModel->getQuizzes($student_id);

include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <h2>Available Quizzes</h2>

    <p class="muted">
        Attempt quizzes from enrolled courses.
    </p>

    <br>

    <table>

        <tr>
            <th>Course</th>
            <th>Quiz</th>
            <th>Marks</th>
            <th>Pass Mark</th>
            <th>Time</th>
            <th>Action</th>
        </tr>

        <?php while(
            $quiz =
            $quizzes->fetch_assoc()
        ): ?>

            <tr>

                <td>
                    <?= htmlspecialchars(
                        $quiz["course_title"]
                    ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $quiz["title"]
                    ) ?>
                </td>

                <td>
                    <?= $quiz["total_marks"] ?>
                </td>

                <td>
                    <?= $quiz["pass_mark"] ?>
                </td>

                <td>
                    <?= $quiz["time_limit_minutes"] ?>
                    min
                </td>

                <td>

                    <a
                        href="take_quiz.php?quiz_id=<?= $quiz["id"] ?>"
                        class="btn"
                    >
                        Start Quiz
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>