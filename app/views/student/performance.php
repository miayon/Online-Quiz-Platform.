<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";

$total_attempts =
$overall["total_attempts"] ?? 0;

$average_score =
$overall["average_score"] ?? 0;

$total_pass =
$overall["total_pass"] ?? 0;

$pass_rate = 0;

if ($total_attempts > 0) {

    $pass_rate = round(
        ($total_pass / $total_attempts)
        * 100,
        2
    );
}

$class_avg =
$classAverage["class_avg"] ?? 0;
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
    ">

        <div>
            <h2>Performance Dashboard</h2>

            <p class="muted">
                Analyze your quiz performance and learning progress.
            </p>
        </div>

        <div class="badge">
            Analytics
        </div>

    </div>

</div>

<div class="grid-4">

    <div class="stat-card">
        <h4>Total Attempts</h4>

        <h2>
            <?= $total_attempts ?>
        </h2>
    </div>

    <div class="stat-card">
        <h4>Average Score</h4>

        <h2>
            <?= $average_score ?>
        </h2>
    </div>

    <div class="stat-card">
        <h4>Pass Rate</h4>

        <h2>
            <?= $pass_rate ?>%
        </h2>
    </div>

    <div class="stat-card">
        <h4>Class Average</h4>

        <h2>
            <?= $class_avg ?>
        </h2>
    </div>

</div>

<br>

<div class="card">

    <h2>Performance Analysis</h2>

    <br>

    <?php if (
        $average_score >= $class_avg
    ): ?>

        <div class="alert alert-success">

            Your average score is above
            or equal to class average.

        </div>

    <?php else: ?>

        <div class="alert alert-error">

            Your average score is below
            class average.

        </div>

    <?php endif; ?>

    <?php if ($pass_rate >= 80): ?>

        <div class="alert alert-success">
            Excellent performance level.
        </div>

    <?php elseif ($pass_rate >= 50): ?>

        <div class="alert">
            Moderate performance level.
        </div>

    <?php else: ?>

        <div class="alert alert-error">
            Performance improvement needed.
        </div>

    <?php endif; ?>

</div>

<div class="card">

    <h2>Subject-wise Performance</h2>

    <br>

    <table>

        <tr>
            <th>Subject</th>
            <th>Average Score</th>
            <th>Total Attempts</th>
            <th>Status</th>
        </tr>

        <?php if (
            $subjects->num_rows > 0
        ): ?>

            <?php while(
                $subject =
                $subjects->fetch_assoc()
            ): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars(
                            $subject["subject_name"]
                        ) ?>
                    </td>

                    <td>
                        <?= $subject["average_score"] ?>
                    </td>

                    <td>
                        <?= $subject["attempts"] ?>
                    </td>

                    <td>

                        <?php if (
                            $subject["average_score"]
                            >= 80
                        ): ?>

                            <span class="badge">
                                Excellent
                            </span>

                        <?php elseif (
                            $subject["average_score"]
                            >= 50
                        ): ?>

                            <span class="badge">
                                Moderate
                            </span>

                        <?php else: ?>

                            <span class="
                                badge
                            " style="
                                background:#fee2e2;
                                color:#991b1b;
                            ">
                                Weak
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="4">
                    No performance data found.
                </td>

            </tr>

        <?php endif; ?>

    </table>

</div>

<br>

<div class="card">

    <h2>Recent Quiz Results</h2>

    <br>

    <table>

        <tr>
            <th>Quiz</th>
            <th>Score</th>
            <th>Completed At</th>
        </tr>

        <?php if (
            $recent->num_rows > 0
        ): ?>

            <?php while(
                $row =
                $recent->fetch_assoc()
            ): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars(
                            $row["title"]
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

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="3">
                    No recent result found.
                </td>

            </tr>

        <?php endif; ?>

    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>