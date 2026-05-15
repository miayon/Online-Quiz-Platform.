<?php
// views/student_report.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/ReportModel.php';

$student_id = isset($_GET['search']) ? $_GET['search'] : '';
$student = null;
$attempts = [];

if ($student_id) {
    $student = ReportModel::getStudentSummary($student_id);
    if ($student) {
        $attempts = ReportModel::getStudentAttempts($student_id);
    }
}
?>

<div class="table-container">
    <h2>Student Academic Performance Report</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Search for a student by their Name or Student ID to view their full academic summary.</p>

    <form action="student_report.php" method="GET" style="margin-bottom: 30px; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Enter Student ID or Name..." value="<?php echo htmlspecialchars($student_id); ?>" 
               style="flex-grow: 1; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" class="btn btn-edit">Search Report</button>
    </form>

    <?php if ($student): ?>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 20px;">
            <div style="background: #fcfcfc; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
                <h3>Student Overview</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Program:</strong> <?php echo htmlspecialchars($student['program']); ?></p>
                <hr>
                <p><strong>Enrolled Courses:</strong> <?php echo $student['enrolled_courses']; ?></p>
                <p><strong>Total Quizzes Taken:</strong> <?php echo $student['total_attempts']; ?></p>
                <p><strong>Average Score:</strong> <span style="font-size: 20px; color: var(--primary); font-weight: bold;"><?php echo number_format($student['avg_score'], 2); ?></span></p>
            </div>

            <div>
                <h3>Quiz Attempt History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Quiz Title</th>
                            <th>Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attempts as $a): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($a['started_at'])); ?></td>
                            <td><?php echo htmlspecialchars($a['course_title']); ?></td>
                            <td><?php echo htmlspecialchars($a['quiz_title']); ?></td>
                            <td><strong><?php echo $a['score']; ?></strong></td>
                            <td>
                                <span class="badge" style="background: <?php echo $a['completed_at'] ? '#e8f5e9' : '#fff3e0'; ?>; color: <?php echo $a['completed_at'] ? '#2e7d32' : '#f57c00'; ?>;">
                                    <?php echo $a['completed_at'] ? 'Completed' : 'In Progress'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($attempts)): ?>
                        <tr><td colspan="5" style="text-align: center;">No quiz attempts found for this student.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($student_id): ?>
        <div style="text-align: center; padding: 50px; color: var(--danger);">
            No student found matching "<?php echo htmlspecialchars($student_id); ?>"
        </div>
    <?php endif; ?>
</div>

</body>
</html>
