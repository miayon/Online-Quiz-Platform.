<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <h2>Attempt History</h2>

    <br>

    <table>

        <tr>
            <th>Course</th>
            <th>Quiz</th>
            <th>Score</th>
            <th>Completed</th>
            <th>Result</th>
        </tr>

        <?php while(
            $row =
            $attempts->fetch_assoc()
        ): ?>

            <tr>

                <td>
                    <?= htmlspecialchars(
                        $row["course_title"]
                    ) ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $row["quiz_title"]
                    ) ?>
                </td>

                <td>
                    <?= $row["score"] ?>
                    /
                    <?= $row["total_marks"] ?>
                </td>

                <td>
                    <?= $row["completed_at"] ?>
                </td>

                <td>

                    <a
                        href="result.php?attempt_id=<?= $row["id"] ?>"
                        class="btn"
                    >
                        View
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>