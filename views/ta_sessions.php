<?php
require_once __DIR__ . '/../models/TAModel.php';
require_once __DIR__ . '/ta_header.php';

$taId = intval($_SESSION['user_id']);
$courseId = intval($_GET['course_id'] ?? 0);
$course = TAModel::getCourse($taId, $courseId);
if (!$course) die("Course not found.");

$sessions = TAModel::getDoubtSessions($courseId, $taId);

ta_course_tabs($courseId);
?>

<div class="grid-2">
    <div class="card">
        <h2>Schedule Doubt Session</h2>

        <form method="POST" action="../controllers/ta_controller.php">
            <input type="hidden" name="action" value="create_session">
            <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">

            <div class="form-group">
                <label>Session Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Date/Time</label>
                <input type="datetime-local" name="scheduled_at" required>
            </div>

            <div class="form-group">
                <label>Duration Minutes</label>
                <input type="number" name="duration_minutes" value="60" min="1" required>
            </div>

            <div class="form-group">
                <label>Location or Meeting Link</label>
                <input type="text" name="location_or_link" required>
            </div>

            <div class="form-group">
                <label>Maximum Attendees</label>
                <input type="number" name="max_attendees" value="20" min="1" required>
            </div>

            <button class="btn btn-primary" type="submit">Create Session</button>
        </form>
    </div>

    <div class="card">
        <h2>My Doubt Sessions</h2>

        <?php if (!$sessions): ?>
            <p>No sessions found.</p>
        <?php endif; ?>

        <?php foreach ($sessions as $session): ?>
            <?php $bookings = TAModel::getSessionBookings($session['id']); ?>

            <details>
                <summary>
                    <?php echo h($session['title']); ?>
                    <span class="badge <?php echo $session['session_status'] === 'cancelled' ? 'badge-danger' : 'badge-success'; ?>">
                        <?php echo h($session['session_status']); ?>
                    </span>
                </summary>

                <p><strong>Scheduled:</strong> <?php echo h($session['scheduled_at']); ?></p>
                <p><strong>Duration:</strong> <?php echo h($session['duration_minutes']); ?> minutes</p>
                <p><strong>Location:</strong> <?php echo h($session['location_or_link']); ?></p>
                <p><strong>Bookings:</strong> <?php echo count($bookings); ?> / <?php echo h($session['max_attendees']); ?></p>

                <?php if ($session['notice']): ?>
                    <p class="notice"><?php echo h($session['notice']); ?></p>
                <?php endif; ?>

                <h4>Attending Students</h4>
                <?php if (!$bookings): ?>
                    <p>No bookings yet.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($bookings as $booking): ?>
                            <li><?php echo h($booking['student_name']); ?> — <?php echo h($booking['student_email']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="../controllers/ta_controller.php">
                    <input type="hidden" name="action" value="reschedule_session">
                    <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                    <input type="hidden" name="session_id" value="<?php echo intval($session['id']); ?>">

                    <div class="form-group">
                        <label>New Date/Time</label>
                        <input type="datetime-local" name="scheduled_at" required>
                    </div>

                    <div class="form-group">
                        <label>Duration Minutes</label>
                        <input type="number" name="duration_minutes" value="<?php echo h($session['duration_minutes']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Location or Meeting Link</label>
                        <input type="text" name="location_or_link" value="<?php echo h($session['location_or_link']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Notice</label>
                        <textarea name="notice" rows="2" required>Session has been rescheduled.</textarea>
                    </div>

                    <button class="btn btn-warning" type="submit">Reschedule</button>
                </form>

                <form method="POST" action="../controllers/ta_controller.php" onsubmit="return confirm('Cancel this session?');">
                    <input type="hidden" name="action" value="cancel_session">
                    <input type="hidden" name="course_id" value="<?php echo intval($courseId); ?>">
                    <input type="hidden" name="session_id" value="<?php echo intval($session['id']); ?>">

                    <div class="form-group">
                        <label>Cancellation Notice</label>
                        <input type="text" name="notice" value="Session has been cancelled." required>
                    </div>

                    <button class="btn btn-danger" type="submit">Cancel Session</button>
                </form>
            </details>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/ta_footer.php'; ?>
