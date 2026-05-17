function loadQuizAnalytics(quizId) {
    const box = document.getElementById("analyticsBox");
    box.style.display = "block";
    box.innerHTML = "Loading analytics...";

    fetch("../api/quiz_analytics.php?quiz_id=" + encodeURIComponent(quizId))
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                box.innerHTML = data.message || "Unable to load analytics.";
                return;
            }

            const a = data.analytics;
            const d = a.distribution;

            box.innerHTML = `
                <h3>${a.title}</h3>
                <div class="grid">
                    <div class="stat-card"><span>Total Attempts</span><strong>${a.total_attempts}</strong></div>
                    <div class="stat-card"><span>Class Average</span><strong>${a.class_average}</strong></div>
                    <div class="stat-card"><span>Highest Score</span><strong>${a.highest_score}</strong></div>
                    <div class="stat-card"><span>Lowest Score</span><strong>${a.lowest_score}</strong></div>
                    <div class="stat-card"><span>Pass Rate</span><strong>${a.pass_rate}%</strong></div>
                </div>
                <h4>Score Distribution</h4>
                <div class="chart-row">
                    <div class="chart-bar">0-40<br><strong>${d.range_0_40 || 0}</strong></div>
                    <div class="chart-bar">41-60<br><strong>${d.range_41_60 || 0}</strong></div>
                    <div class="chart-bar">61-80<br><strong>${d.range_61_80 || 0}</strong></div>
                    <div class="chart-bar">81-100<br><strong>${d.range_81_100 || 0}</strong></div>
                </div>
            `;
        })
        .catch(() => {
            box.innerHTML = "AJAX request failed.";
        });
}
