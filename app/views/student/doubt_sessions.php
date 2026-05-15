<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">
        <div>
            <h2>Doubt Sessions</h2>
            <p class="muted">
                Book live doubt-clearing sessions with assigned teaching assistants.
            </p>
        </div>

        <span class="badge">
            Live Support
        </span>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="GET">

        <div style="
            display:grid;
            grid-template-columns:1fr auto;
            gap:15px;
            align-items:end;
        ">

            <div>
                <label>Filter by Course</label>

                <select
                    name="course_id"
                    class="form-control"
                >
                    <option value="">
                        All Courses
                    </option>

                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <option
                            value="<?= (int)$course["id"] ?>"
                            <?= ($course_id == $course["id"]) ? "selected" : "" ?>
                        >
                            <?= htmlspecialchars($course["title"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button
                type="submit"
                class="btn"
            >
                Filter
            </button>

        </div>

    </form>

</div>

<div class="card">

    <h2>Upcoming Sessions</h2>

    <br>

    <table>
        <tr>
            <th>Session</th>
            <th>Course</th>
            <th>TA</th>
            <th>Schedule</th>
            <th>Seats</th>
            <th>Location / Link</th>
            <th>Action</th>
        </tr>

        <?php if ($sessions->num_rows > 0): ?>

            <?php while ($session = $sessions->fetch_assoc()): ?>

                <?php
                    $isFull =
                        $session["booked_count"] >=
                        $session["max_attendees"];

                    $alreadyBooked =
                        $session["already_booked"] > 0;
                ?>

                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars($session["title"]) ?>
                        </strong>
                        <br>
                        <small class="muted">
                            <?= (int)$session["duration_minutes"] ?> minutes
                        </small>
                    </td>

                    <td>
                        <?= htmlspecialchars($session["course_title"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($session["ta_name"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($session["scheduled_at"]) ?>
                    </td>

                    <td>
                        <span class="badge">
                            <?= (int)$session["booked_count"] ?>
                            /
                            <?= (int)$session["max_attendees"] ?>
                        </span>
                    </td>

                    <td>
                        <?php if (
                            str_starts_with(
                                $session["location_or_link"],
                                "http"
                            )
                        ): ?>

                            <a
                                href="<?= htmlspecialchars($session["location_or_link"]) ?>"
                                target="_blank"
                                class="btn"
                            >
                                Open Link
                            </a>

                        <?php else: ?>

                            <?= htmlspecialchars($session["location_or_link"]) ?>

                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($alreadyBooked): ?>

                            <span class="badge">
                                Booked
                            </span>

                        <?php elseif ($isFull): ?>

                            <span
                                class="badge"
                                style="
                                    background:#fee2e2;
                                    color:#991b1b;
                                "
                            >
                                Full
                            </span>

                        <?php else: ?>

                            <a
                                href="book_session.php?session_id=<?= (int)$session["id"] ?>"
                                class="btn btn-success"
                            >
                                Book
                            </a>

                        <?php endif; ?>
                    </td>
                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="7">
                    No upcoming doubt sessions found.
                </td>
            </tr>

        <?php endif; ?>
    </table>

</div>

<div class="card">

    <h2>My Upcoming Bookings</h2>

    <br>

    <table>
        <tr>
            <th>Session</th>
            <th>Course</th>
            <th>TA</th>
            <th>Schedule</th>
            <th>Location / Link</th>
        </tr>

        <?php if ($bookings->num_rows > 0): ?>

            <?php while ($booking = $bookings->fetch_assoc()): ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($booking["title"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($booking["course_title"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($booking["ta_name"]) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($booking["scheduled_at"]) ?>
                    </td>

                    <td>
                        <?php if (
                            str_starts_with(
                                $booking["location_or_link"],
                                "http"
                            )
                        ): ?>

                            <a
                                href="<?= htmlspecialchars($booking["location_or_link"]) ?>"
                                target="_blank"
                                class="btn"
                            >
                                Join
                            </a>

                        <?php else: ?>

                            <?= htmlspecialchars($booking["location_or_link"]) ?>

                        <?php endif; ?>
                    </td>
                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="5">
                    You have no upcoming bookings.
                </td>
            </tr>

        <?php endif; ?>
    </table>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>