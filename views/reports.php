<?php
// views/reports.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/ReportModel.php';

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$subjectEnrollments = ReportModel::getEnrollmentPerSubject($startDate, $endDate);
$passRates = ReportModel::getQuizPassRates($startDate, $endDate);
$topInstructors = ReportModel::getMostActiveInstructors();
$peakTimes = ReportModel::getPeakUsageTimes();

// Date range calculation for Semester Overview Cards
$start = $startDate ? $startDate : date('Y-m-d', strtotime('-90 days'));
$end = $endDate ? $endDate : date('Y-m-d');
$rangeStats = ReportModel::getRangeSummary($start, $end);
?>

<div class="table-container" style="margin-bottom: 20px;">
    <h3>Institutional Report Filter (Semester Range)</h3>
    <form action="reports.php" method="GET" class="flex-form">
        <div>
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Start Date</label>
            <input type="date" name="start_date" value="<?php echo htmlspecialchars($startDate ?? $start); ?>" class="form-control">
        </div>
        <div>
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">End Date</label>
            <input type="date" name="end_date" value="<?php echo htmlspecialchars($endDate ?? $end); ?>" class="form-control">
        </div>
        <div style="flex: 0 0 auto;">
            <button type="submit" class="btn btn-edit" style="width: 150px; padding: 10px;">Filter Results</button>
            <a href="reports.php" class="btn btn-reject" style="width: 150px; padding: 10px; display: inline-block; text-align: center; text-decoration: none; box-sizing: border-box; background: #607d8b; color: white;">Clear Filter</a>
        </div>
    </form>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
    <div class="stat-card" style="background: rgba(25, 118, 210, 0.04); border: 1px solid rgba(25, 118, 210, 0.12); border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h4 style="margin: 0; color: #1976d2; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">New Signups</h4>
        <div style="font-size: 32px; font-weight: 700; margin: 12px 0; color: #1565c0;"><?php echo $rangeStats['users_created']; ?></div>
        <p style="margin: 0; font-size: 11px; color: #777;">In selected timeframe</p>
    </div>
    <div class="stat-card" style="background: rgba(46, 125, 50, 0.04); border: 1px solid rgba(46, 125, 50, 0.12); border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h4 style="margin: 0; color: #2e7d32; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Courses Created</h4>
        <div style="font-size: 32px; font-weight: 700; margin: 12px 0; color: #2e7d32;"><?php echo $rangeStats['courses_created']; ?></div>
        <p style="margin: 0; font-size: 11px; color: #777;">In selected timeframe</p>
    </div>
    <div class="stat-card" style="background: rgba(230, 81, 0, 0.04); border: 1px solid rgba(230, 81, 0, 0.12); border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h4 style="margin: 0; color: #e65100; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Quizzes Created</h4>
        <div style="font-size: 32px; font-weight: 700; margin: 12px 0; color: #e65100;"><?php echo $rangeStats['quizzes_created']; ?></div>
        <p style="margin: 0; font-size: 11px; color: #777;">In selected timeframe</p>
    </div>
    <div class="stat-card" style="background: rgba(106, 27, 154, 0.04); border: 1px solid rgba(106, 27, 154, 0.12); border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <h4 style="margin: 0; color: #6a1b9a; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Total Attempts</h4>
        <div style="font-size: 32px; font-weight: 700; margin: 12px 0; color: #6a1b9a;"><?php echo $rangeStats['attempts_created']; ?></div>
        <p style="margin: 0; font-size: 11px; color: #777;">In selected timeframe</p>
    </div>
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

<div class="table-container" style="margin-top: 20px; padding: 25px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 6px rgba(0,0,0,0.01);">
    <h2 style="font-size: 20px; font-weight: 600; margin-top: 0; margin-bottom: 5px;">Peak Activity Hours (Student Quiz Attempts)</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 25px;">Aggregated study behavior showing student engagement distribution across 24 hours.</p>
    <div style="display: flex; gap: 8px; align-items: flex-end; height: 160px; padding: 25px 15px 15px 15px; background: rgba(0,0,0,0.01); border-radius: 12px; border: 1px solid rgba(0,0,0,0.03);">
        <?php 
        // Build a complete 24 hour array with 0 attempts default
        $hours = array_fill(0, 24, 0);
        $max_count = 1; // avoid division by zero
        foreach ($peakTimes as $pt) {
            $hours[$pt['hour']] = $pt['attempt_count'];
            if ($pt['attempt_count'] > $max_count) {
                $max_count = $pt['attempt_count'];
            }
        }
        
        for ($h = 0; $h < 24; $h++):
            $count = $hours[$h];
            $height = ($count / $max_count) * 110; // max height 110px
            $display_hour = ($h == 0) ? '12 AM' : (($h < 12) ? $h . ' AM' : (($h == 12) ? '12 PM' : ($h - 12) . ' PM'));
        ?>
        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%;">
            <div style="width: 100%; height: <?php echo $height; ?>px; background: <?php echo $count == $max_count ? 'var(--accent)' : 'var(--primary)'; ?>; border-radius: 4px; position: relative;" title="<?php echo $count; ?> attempts at <?php echo $display_hour; ?>">
                <?php if ($count > 0): ?>
                    <span style="position: absolute; top: -18px; left: 0; right: 0; text-align: center; font-size: 10px; font-weight: 600; color: #333;"><?php echo $count; ?></span>
                <?php endif; ?>
            </div>
            <span style="font-size: 9px; color: #777; margin-top: 8px; transform: rotate(-45deg); white-space: nowrap;"><?php echo $display_hour; ?></span>
        </div>
        <?php endfor; ?>
    </div>
    <div style="height: 25px;"></div>
</div>

</body>
</html>
