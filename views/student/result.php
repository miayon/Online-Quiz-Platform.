<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";

$passed =
$result["score"] >=
$result["pass_mark"];
?>

<div class="card">

    <h2>
        Quiz Result
    </h2>

    <br>

    <div class="grid-4">

        <div class="stat-card">
            <h4>Quiz</h4>

            <h2 style="font-size:18px;">
                <?= htmlspecialchars(
                    $result["title"]
                ) ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Score</h4>

            <h2>
                <?= $result["score"] ?>
                /
                <?= $result["total_marks"] ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Pass Mark</h4>

            <h2>
                <?= $result["pass_mark"] ?>
            </h2>
        </div>

        <div class="stat-card">
            <h4>Status</h4>

            <h2 style="
                color:
                <?= $passed
                    ? 'green'
                    : 'red' ?>
            ">

                <?= $passed
                    ? "Passed"
                    : "Failed" ?>

            </h2>
        </div>

    </div>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>