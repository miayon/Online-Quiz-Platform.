<?php
// views/manage_quizzes.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/QuizModel.php';

$quizzes = QuizModel::getAllWithDetails();
?>

<div class="table-container">
    <h2>All Platform Quizzes</h2>
    <table>
        <thead>
            <tr>
                <th>Quiz Title</th>
                <th>Course</th>
                <th>Created By</th>
                <th>Type</th>
                <th>Attempts</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quizzes as $quiz): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($quiz['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($quiz['course_title'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($quiz['creator_name'] ?? 'N/A'); ?></td>
                <td><?php echo strtoupper($quiz['quiz_type']); ?></td>
                <td><?php echo $quiz['attempt_count']; ?></td>
                <td>
                    <span class="badge" style="background: <?php 
                        echo $quiz['status'] == 'published' ? '#e8f5e9' : '#f5f5f5'; 
                    ?>; color: <?php 
                        echo $quiz['status'] == 'published' ? '#2e7d32' : '#616161'; 
                    ?>">
                        <?php echo strtoupper($quiz['status']); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($quizzes)): ?>
            <tr><td colspan="6" style="text-align: center;">No quizzes found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
