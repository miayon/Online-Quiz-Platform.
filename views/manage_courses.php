<?php
// views/manage_courses.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/CourseModel.php';

$courses = CourseModel::getAllWithDetails();
?>

<div class="table-container">
    <h2>All Platform Courses</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Instructor</th>
                <th>Subject</th>
                <th>Students</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($course['subject_name'] ?? 'N/A'); ?></td>
                <td><?php echo $course['student_count']; ?> / <?php echo $course['max_students']; ?></td>
                <td>
                    <span class="badge" style="background: <?php 
                        echo $course['status'] == 'active' ? '#e8f5e9' : ($course['status'] == 'draft' ? '#f5f5f5' : '#ffebee'); 
                    ?>; color: <?php 
                        echo $course['status'] == 'active' ? '#2e7d32' : ($course['status'] == 'draft' ? '#616161' : '#c62828'); 
                    ?>">
                        <?php echo strtoupper($course['status']); ?>
                    </span>
                </td>
                <td><?php echo date('M d, Y', strtotime($course['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($courses)): ?>
            <tr><td colspan="6" style="text-align: center;">No courses found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
