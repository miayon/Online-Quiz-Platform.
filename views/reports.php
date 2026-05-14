<?php
// views/reports.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/ReportModel.php';

$subjectEnrollments = ReportModel::getEnrollmentPerSubject();
$passRates = ReportModel::getQuizPassRates();
$topInstructors = ReportModel::getMostActiveInstructors();
?>

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
