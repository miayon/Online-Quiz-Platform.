<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courses = TAModel::getAssignedCourses($taId);
?>

<div class="course-grid">
    <?php if (!$courses): ?>
        <div class="card">No assigned courses found.</div>
    <?php endif; ?>

    <?php foreach ($courses as $course): ?>
        <div class="card">
            <h2><?php echo h($course['title']); ?></h2>
            <p><?php echo h($course['description']); ?></p>
            <p><strong>Subject:</strong> <?php echo h($course['subject_name']); ?></p>
            <p><strong>Instructor:</strong> <?php echo h($course['instructor_name']); ?></p>
            <p><strong>Status:</strong> <span class="badge badge-info"><?php echo h($course['status']); ?></span></p>
            <a class="btn btn-primary" href="ta_course_detail.php?course_id=<?php echo intval($course['id']); ?>">Manage Course</a>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
