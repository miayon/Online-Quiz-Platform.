<?php

class DoubtSessionModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function enrolledCourses($student_id) {

        $stmt = $this->conn->prepare("
            SELECT
                c.id,
                c.title
            FROM enrollments e
            JOIN courses c ON c.id = e.course_id
            WHERE e.student_id = ?
            AND e.status = 'active'
            ORDER BY c.title ASC
        ");

        $stmt->bind_param("i", $student_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getSessions($student_id, $course_id = "") {

        $sql = "
            SELECT
                d.*,
                c.title AS course_title,
                u.name AS ta_name,

                (
                    SELECT COUNT(*)
                    FROM doubt_session_bookings b
                    WHERE b.doubt_session_id = d.id
                ) AS booked_count,

                (
                    SELECT COUNT(*)
                    FROM doubt_session_bookings b2
                    WHERE b2.doubt_session_id = d.id
                    AND b2.student_id = ?
                ) AS already_booked

            FROM doubt_sessions d

            JOIN courses c ON c.id = d.course_id
            JOIN users u ON u.id = d.ta_id
            JOIN enrollments e ON e.course_id = c.id

            WHERE e.student_id = ?
            AND e.status = 'active'
            AND d.scheduled_at >= NOW()
        ";

        $params = [$student_id, $student_id];
        $types = "ii";

        if (!empty($course_id)) {
            $sql .= " AND d.course_id = ?";
            $params[] = $course_id;
            $types .= "i";
        }

        $sql .= " ORDER BY d.scheduled_at ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function bookSession($student_id, $session_id) {

        $check = $this->conn->prepare("
            SELECT id
            FROM doubt_session_bookings
            WHERE doubt_session_id = ?
            AND student_id = ?
            LIMIT 1
        ");

        $check->bind_param("ii", $session_id, $student_id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            return [
                "status" => false,
                "message" => "You already booked this session."
            ];
        }

        $capacity = $this->conn->prepare("
            SELECT
                d.max_attendees,
                (
                    SELECT COUNT(*)
                    FROM doubt_session_bookings b
                    WHERE b.doubt_session_id = d.id
                ) AS booked_count
            FROM doubt_sessions d
            WHERE d.id = ?
            LIMIT 1
        ");

        $capacity->bind_param("i", $session_id);
        $capacity->execute();

        $session = $capacity->get_result()->fetch_assoc();

        if (!$session) {
            return [
                "status" => false,
                "message" => "Session not found."
            ];
        }

        if ($session["booked_count"] >= $session["max_attendees"]) {
            return [
                "status" => false,
                "message" => "This session is already full."
            ];
        }

        $stmt = $this->conn->prepare("
            INSERT INTO doubt_session_bookings
            (
                doubt_session_id,
                student_id
            )
            VALUES (?, ?)
        ");

        $stmt->bind_param("ii", $session_id, $student_id);

        if ($stmt->execute()) {
            return [
                "status" => true,
                "message" => "Session booked successfully."
            ];
        }

        return [
            "status" => false,
            "message" => "Booking failed."
        ];
    }

    public function myBookings($student_id) {

        $stmt = $this->conn->prepare("
            SELECT
                b.*,
                d.title,
                d.scheduled_at,
                d.duration_minutes,
                d.location_or_link,
                c.title AS course_title,
                u.name AS ta_name
            FROM doubt_session_bookings b
            JOIN doubt_sessions d ON d.id = b.doubt_session_id
            JOIN courses c ON c.id = d.course_id
            JOIN users u ON u.id = d.ta_id
            WHERE b.student_id = ?
            AND d.scheduled_at >= NOW()
            ORDER BY d.scheduled_at ASC
        ");

        $stmt->bind_param("i", $student_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}
?>
