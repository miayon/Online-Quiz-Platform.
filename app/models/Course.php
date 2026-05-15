<?php

class Course {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSubjects() {

        return $this->conn->query("
            SELECT *
            FROM subjects
            ORDER BY name ASC
        ");
    }

    public function getCourses(
        $student_id,
        $search = "",
        $subject_id = ""
    ) {

        $sql = "
            SELECT
                c.*,
                s.name AS subject_name,
                u.name AS instructor_name,

                COUNT(e.id) AS enrolled_count,

                (
                    SELECT status
                    FROM enrollments
                    WHERE student_id = ?
                    AND course_id = c.id
                    LIMIT 1
                ) AS my_status

            FROM courses c

            JOIN subjects s
            ON s.id = c.subject_id

            JOIN users u
            ON u.id = c.instructor_id

            LEFT JOIN enrollments e
            ON e.course_id = c.id
            AND e.status = 'active'

            WHERE c.status = 'active'
        ";

        $params = [$student_id];
        $types = "i";

        if (!empty($search)) {

            $sql .= "
                AND (
                    c.title LIKE ?
                    OR c.description LIKE ?
                )
            ";

            $like = "%" . $search . "%";

            $params[] = $like;
            $params[] = $like;

            $types .= "ss";
        }

        if (!empty($subject_id)) {

            $sql .= "
                AND c.subject_id = ?
            ";

            $params[] = $subject_id;

            $types .= "i";
        }

        $sql .= "
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            $types,
            ...$params
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function enroll(
        $student_id,
        $course_id
    ) {

        $check = $this->conn->prepare("
            SELECT id
            FROM enrollments
            WHERE student_id = ?
            AND course_id = ?
        ");

        $check->bind_param(
            "ii",
            $student_id,
            $course_id
        );

        $check->execute();

        if (
            $check->get_result()->num_rows > 0
        ) {
            return false;
        }

        $course = $this->conn->prepare("
            SELECT enrollment_type
            FROM courses
            WHERE id = ?
        ");

        $course->bind_param("i", $course_id);

        $course->execute();

        $courseData =
            $course->get_result()->fetch_assoc();

        $status = "pending";

        if (
            $courseData["enrollment_type"]
            === "open"
        ) {
            $status = "active";
        }

        $stmt = $this->conn->prepare("
            INSERT INTO enrollments
            (
                student_id,
                course_id,
                status
            )
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param(
            "iis",
            $student_id,
            $course_id,
            $status
        );

        return $stmt->execute();
    }

    public function courseDetails(
        $student_id,
        $course_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                c.*,
                s.name AS subject_name,
                u.name AS instructor_name,
                e.status AS enrollment_status

            FROM courses c

            JOIN subjects s
            ON s.id = c.subject_id

            JOIN users u
            ON u.id = c.instructor_id

            JOIN enrollments e
            ON e.course_id = c.id

            WHERE e.student_id = ?
            AND c.id = ?
        ");

        $stmt->bind_param(
            "ii",
            $student_id,
            $course_id
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function dropCourse(
        $student_id,
        $course_id
    ) {

        $stmt = $this->conn->prepare("
            UPDATE enrollments
            SET status = 'dropped'
            WHERE student_id = ?
            AND course_id = ?
        ");

        $stmt->bind_param(
            "ii",
            $student_id,
            $course_id
        );

        return $stmt->execute();
    }
}
?>