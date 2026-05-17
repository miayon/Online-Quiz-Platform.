<?php
// views/manage_courses.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/CourseModel.php';
require_once __DIR__ . '/../models/SubjectModel.php';

$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$instructor_id = isset($_GET['instructor_id']) ? $_GET['instructor_id'] : '';

$courses = CourseModel::getAllWithDetails($subject_id, $instructor_id);
$subjects = SubjectModel::getAll();
$instructors = db_fetch_all("SELECT * FROM users WHERE role = 'instructor' AND is_active = 1");
?>

<div class="table-container" style="margin-bottom: 20px;">
    <h2>Filter Courses</h2>
    <form action="manage_courses.php" method="GET" class="flex-form">
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Subject</label>
            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Subjects</option>
                <?php foreach ($subjects as $sub): ?>
                    <option value="<?php echo $sub['id']; ?>" <?php echo $subject_id == $sub['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($sub['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Instructor</label>
            <select name="instructor_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Instructors</option>
                <?php foreach ($instructors as $inst): ?>
                    <option value="<?php echo $inst['id']; ?>" <?php echo $instructor_id == $inst['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($inst['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="flex: 0 0 auto;">
            <label style="margin-bottom: 5px; display: block; opacity: 0;">Clear</label>
            <a href="manage_courses.php" class="btn btn-reject" style="width: 150px; padding: 10px; display: inline-block; text-align: center; text-decoration: none; box-sizing: border-box; background: #607d8b; color: white; border-radius: 6px;">Clear Filters</a>
        </div>
    </form>
</div>

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
