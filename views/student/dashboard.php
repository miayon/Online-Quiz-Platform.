<?php
require_once __DIR__ . "/init.php";

$stats = $studentModel->getDashboardStats($student_id);
$courses = $studentModel->getDashboardCourses($student_id);

include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="grid-4">
    <div class="stat-card">
        <h4>Active Courses</h4>
        <h2><?= (int)$stats["active_courses"] ?></h2>
    </div>

    <div class="stat-card">
        <h4>Total Attempts</h4>
        <h2><?= (int)$stats["total_attempts"] ?></h2>
    </div>

    <div class="stat-card">
        <h4>Average Score</h4>
        <h2><?= htmlspecialchars($stats["average_score"]) ?></h2>
    </div>

    <div class="stat-card">
        <h4>Booked Sessions</h4>
        <h2><?= (int)$stats["booked_sessions"] ?></h2>
    </div>
</div>

<div class="card">
    <h2>My Courses</h2>
    <p class="muted">Your enrolled courses and next upcoming quizzes.</p>

    <br>

    <table>
        <tr>
            <th>Course</th>
            <th>Description</th>
            <th>Status</th>
            <th>Next Quiz</th>
            <th>Action</th>
        </tr>

        <?php if ($courses->num_rows > 0): ?>
            <?php while ($row = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["title"]) ?></td>
                    <td><?= htmlspecialchars($row["description"]) ?></td>
                    <td>
                        <span class="badge">
                            <?= htmlspecialchars($row["status"]) ?>
                        </span>
                    </td>
                    <td>
                        <?= $row["next_quiz"] 
                            ? htmlspecialchars($row["next_quiz"]) 
                            : "No upcoming quiz" 
                        ?>
                    </td>
                    <td>
                        <a class="btn" href="course_details.php?id=<?= (int)$row['id'] ?>">
                            View
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No enrolled course found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php
include __DIR__ . "/partials/footer.php";
?>