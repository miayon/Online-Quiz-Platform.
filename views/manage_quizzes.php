<?php
// views/manage_quizzes.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/QuizModel.php';

$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$quiz_type = isset($_GET['quiz_type']) ? $_GET['quiz_type'] : '';

$quizzes = QuizModel::getAllWithDetails($course_id, $status, $quiz_type);
$courses = db_fetch_all("SELECT id, title FROM courses");
?>

<div class="table-container" style="margin-bottom: 20px;">
    <h2>Filter Quizzes</h2>
    <form action="manage_quizzes.php" method="GET" class="flex-form">
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Course</label>
            <select name="course_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Courses</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo $course_id == $c['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Status</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="draft" <?php echo $status == 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?php echo $status == 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
        </div>
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Type</label>
            <select name="quiz_type" class="form-control" onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="graded" <?php echo $quiz_type == 'graded' ? 'selected' : ''; ?>>Graded</option>
                <option value="practice" <?php echo $quiz_type == 'practice' ? 'selected' : ''; ?>>Practice</option>
            </select>
        </div>
        <div style="flex: 0 0 auto;">
            <label style="margin-bottom: 5px; display: block; opacity: 0;">Clear</label>
            <a href="manage_quizzes.php" class="btn btn-reject" style="width: 150px; padding: 10px; display: inline-block; text-align: center; text-decoration: none; box-sizing: border-box; background: #607d8b; color: white; border-radius: 6px;">Clear Filters</a>
        </div>
    </form>
</div>

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
