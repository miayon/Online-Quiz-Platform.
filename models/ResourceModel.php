<?php

class ResourceModel {

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

    public function getMaterials($student_id, $course_id = "") {

        $sql = "
            SELECT
                m.*,
                c.title AS course_title,
                u.name AS uploaded_by_name
            FROM course_materials m
            JOIN courses c ON c.id = m.course_id
            JOIN users u ON u.id = m.uploaded_by
            JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
            AND e.status = 'active'
        ";

        $params = [$student_id];
        $types = "i";

        if (!empty($course_id)) {
            $sql .= " AND m.course_id = ?";
            $params[] = $course_id;
            $types .= "i";
        }

        $sql .= " ORDER BY m.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param($types, ...$params);

        $stmt->execute();

        return $stmt->get_result();
    }

    public function getAnnouncements($student_id, $course_id = "") {

        $sql = "
            SELECT
                a.*,
                c.title AS course_title,
                u.name AS author_name,
                u.role AS author_role
            FROM announcements a
            JOIN courses c ON c.id = a.course_id
            JOIN users u ON u.id = a.author_id
            JOIN enrollments e ON e.course_id = c.id
            WHERE e.student_id = ?
            AND e.status = 'active'
        ";

        $params = [$student_id];
        $types = "i";

        if (!empty($course_id)) {
            $sql .= " AND a.course_id = ?";
            $params[] = $course_id;
            $types .= "i";
        }

        $sql .= " ORDER BY a.created_at DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param($types, ...$params);

        $stmt->execute();

        return $stmt->get_result();
    }
}
?>
