<?php
require_once __DIR__ . "/init.php";

$quizzes = $quizModel->getQuizzes($student_id);

include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <div>
            <h2>Leaderboard</h2>
            <p class="muted">
                Select a quiz and view top student rankings live.
            </p>
        </div>

        <div class="badge">
            AJAX Enabled
        </div>
    </div>

    <div style="
        display:grid;
        grid-template-columns:1fr auto;
        gap:15px;
        align-items:end;
    ">

        <div>
            <label>Select Quiz</label>

            <select
                id="quiz_id"
                class="form-control"
            >
                <option value="">
                    Choose a quiz
                </option>

                <?php while ($quiz = $quizzes->fetch_assoc()): ?>
                    <option value="<?= (int)$quiz["id"] ?>">
                        <?= htmlspecialchars($quiz["course_title"]) ?>
                        -
                        <?= htmlspecialchars($quiz["title"]) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button
            class="btn"
            onclick="loadLeaderboard()"
        >
            Load Ranking
        </button>

    </div>

</div>

<div
    id="leaderboardBox"
    class="card"
    style="display:none;"
>
    <h2 id="leaderboardTitle">
        Ranking
    </h2>

    <p class="muted" id="leaderboardSubtitle"></p>

    <br>

    <div id="topThree"></div>

    <br>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student</th>
                <th>Student ID</th>
                <th>Best Score</th>
            </tr>
        </thead>

        <tbody id="leaderboardRows"></tbody>
    </table>
</div>

<script>
function loadLeaderboard() {
    const quizId = document.getElementById("quiz_id").value;

    if (!quizId) {
        alert("Please select a quiz first.");
        return;
    }

    const box = document.getElementById("leaderboardBox");
    const rows = document.getElementById("leaderboardRows");
    const topThree = document.getElementById("topThree");

    box.style.display = "block";
    rows.innerHTML = `
        <tr>
            <td colspan="4">Loading...</td>
        </tr>
    `;
    topThree.innerHTML = "";

    fetch("ajax_leaderboard.php?quiz_id=" + quizId)
        .then(response => response.json())
        .then(data => {
            if (data.status !== "success") {
                rows.innerHTML = `
                    <tr>
                        <td colspan="4">${data.message}</td>
                    </tr>
                `;
                return;
            }

            document.getElementById("leaderboardTitle").innerText =
                data.quiz.title + " Leaderboard";

            document.getElementById("leaderboardSubtitle").innerText =
                data.quiz.course_title + " | Total Marks: " + data.quiz.total_marks;

            if (data.leaderboard.length === 0) {
                rows.innerHTML = `
                    <tr>
                        <td colspan="4">No attempt found for this quiz.</td>
                    </tr>
                `;
                return;
            }

            let topHtml = `<div class="rank-grid">`;

            data.leaderboard.slice(0, 3).forEach(student => {
                let medal = "🥉";

                if (student.rank === 1) medal = "🥇";
                if (student.rank === 2) medal = "🥈";

                topHtml += `
                    <div class="rank-card">
                        <div class="rank-medal">${medal}</div>
                        <h3>${student.name}</h3>
                        <p>${student.student_id ?? "N/A"}</p>
                        <h2>${student.score}</h2>
                    </div>
                `;
            });

            topHtml += `</div>`;
            topThree.innerHTML = topHtml;

            let html = "";

            data.leaderboard.forEach(student => {
                html += `
                    <tr>
                        <td>#${student.rank}</td>
                        <td>${student.name}</td>
                        <td>${student.student_id ?? "N/A"}</td>
                        <td><span class="badge">${student.score}</span></td>
                    </tr>
                `;
            });

            rows.innerHTML = html;
        })
        .catch(() => {
            rows.innerHTML = `
                <tr>
                    <td colspan="4">Failed to load leaderboard.</td>
                </tr>
            `;
        });
}
</script>

<?php
include __DIR__ . "/partials/footer.php";
?>