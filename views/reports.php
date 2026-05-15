<?php
// views/reports.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/ReportModel.php';

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$subjectEnrollments = ReportModel::getEnrollmentPerSubject($startDate, $endDate);
$passRates = ReportModel::getQuizPassRates($startDate, $endDate);
$topInstructors = ReportModel::getMostActiveInstructors();
?>

<div class="table-container" style="margin-bottom: 20px;">
    <h3>Institutional Report Filter (Semester Range)</h3>
    <form action="reports.php" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
        <div>
            <label style="font-size: 12px; color: #666;">Start Date</label><br>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label style="font-size: 12px; color: #666;">End Date</label><br>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <button type="submit" class="btn btn-edit">Filter Results</button>
        <a href="reports.php" class="btn" style="background: #eee; color: #333;">Clear Filter</a>
    </form>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="table-container">
        <h2>Enrollments per Subject</h2>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Enrollments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjectEnrollments as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td><?php echo $row['enrollment_count']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2>Most Active Instructors</h2>
        <table>
            <thead>
                <tr>
                    <th>Instructor Name</th>
                    <th>Courses Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topInstructors as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo $row['course_count']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="table-container" style="margin-top: 20px;">
    <h2>Quiz Pass Rates (Graded Quizzes)</h2>
    <table>
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Course</th>
                <th>Total Attempts</th>
                <th>Pass Rate (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($passRates as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                <td><?php echo $row['total_attempts']; ?></td>
                <td>
                    <?php 
                    if ($row['total_attempts'] > 0) {
                        $rate = ($row['pass_count'] / $row['total_attempts']) * 100;
                        echo number_format($rate, 1) . '%';
                    } else {
                        echo '0%';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($passRates)): ?>
            <tr><td colspan="4" style="text-align: center;">No quiz data available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
