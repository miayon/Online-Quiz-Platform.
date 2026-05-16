<?php
// views/manage_integrity_flags.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/IntegrityModel.php';

$reports = IntegrityModel::getAllReports();
?>

<div class="table-container">
    <h2>Academic Integrity & Content Reports</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Review flags reported by Instructors and TAs regarding student conduct or quiz integrity.</p>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reporter</th>
                <th>Target Student</th>
                <th>Quiz</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $report): ?>
            <tr>
                <td><?php echo date('M d, Y', strtotime($report['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($report['reporter_name']); ?></td>
                <td><strong><?php echo htmlspecialchars($report['student_name']); ?></strong></td>
                <td><?php echo htmlspecialchars($report['quiz_title']); ?></td>
                <td><?php echo htmlspecialchars($report['reason']); ?></td>
                <td>
                    <span class="badge" style="background: <?php 
                        echo $report['status'] == 'pending' ? '#fff3e0' : ($report['status'] == 'resolved' ? '#e8f5e9' : '#ffebee'); 
                    ?>; color: <?php 
                        echo $report['status'] == 'pending' ? '#f57c00' : ($report['status'] == 'resolved' ? '#2e7d32' : '#c62828'); 
                    ?>">
                        <?php echo strtoupper($report['status']); ?>
                    </span>
                </td>
                <td class="actions-cell">
                    <?php if ($report['status'] == 'pending'): ?>
                        <a href="../controllers/integrity_controller.php?action=resolve&id=<?php echo $report['id']; ?>" class="btn btn-approve">Resolve</a>
                        <a href="../controllers/integrity_controller.php?action=escalate&id=<?php echo $report['id']; ?>" class="btn btn-edit" style="background: #607d8b;">Escalate</a>
                    <?php endif; ?>
                    <a href="../controllers/integrity_controller.php?action=delete&id=<?php echo $report['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reports)): ?>
            <tr><td colspan="7" style="text-align: center;">No integrity flags reported yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
