<?php

class PerformanceModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function overallStats($student_id) {

        $stmt = $this->conn->prepare("
            SELECT
                COUNT(a.id) AS total_attempts,

                ROUND(
                    AVG(a.score),
                    2
                ) AS average_score,

                SUM(
                    CASE
                        WHEN a.score >= q.pass_mark
                        THEN 1
                        ELSE 0
                    END
                ) AS total_pass

            FROM attempts a

            JOIN quizzes q
            ON q.id = a.quiz_id

            WHERE a.student_id = ?
            AND a.completed_at IS NOT NULL
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function classAverage() {

        $stmt = $this->conn->prepare("
            SELECT
                ROUND(
                    AVG(score),
                    2
                ) AS class_avg
            FROM attempts
            WHERE completed_at IS NOT NULL
        ");

        $stmt->execute();

        return $stmt
            ->get_result()
            ->fetch_assoc();
    }

    public function subjectPerformance(
        $student_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                s.name AS subject_name,

                ROUND(
                    AVG(a.score),
                    2
                ) AS average_score,

                COUNT(a.id) AS attempts

            FROM attempts a

            JOIN quizzes q
            ON q.id = a.quiz_id

            JOIN courses c
            ON c.id = q.course_id

            JOIN subjects s
            ON s.id = c.subject_id

            WHERE a.student_id = ?
            AND a.completed_at IS NOT NULL

            GROUP BY s.id

            ORDER BY average_score DESC
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }

    public function recentResults(
        $student_id
    ) {

        $stmt = $this->conn->prepare("
            SELECT
                q.title,
                a.score,
                q.total_marks,
                a.completed_at

            FROM attempts a

            JOIN quizzes q
            ON q.id = a.quiz_id

            WHERE a.student_id = ?
            AND a.completed_at IS NOT NULL

            ORDER BY a.completed_at DESC

            LIMIT 5
        ");

        $stmt->bind_param(
            "i",
            $student_id
        );

        $stmt->execute();

        return $stmt->get_result();
    }
}
?>
