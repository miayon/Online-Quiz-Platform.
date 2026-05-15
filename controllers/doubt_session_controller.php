<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/DoubtSessionModel.php";

class DoubtSessionController {

    private $sessionModel;

    public function __construct($db) {
        $this->sessionModel = new DoubtSessionModel($db);
    }

    public function index($student_id) {

        $course_id = $_GET["course_id"] ?? "";
        $message = $_GET["message"] ?? "";
        $error = $_GET["error"] ?? "";

        $courses = $this->sessionModel
            ->enrolledCourses($student_id);

        $sessions = $this->sessionModel
            ->getSessions($student_id, $course_id);

        $bookings = $this->sessionModel
            ->myBookings($student_id);

        include __DIR__ . "/../views/student/doubt_sessions.php";
    }

    public function book($student_id) {

        $session_id = $_GET["session_id"] ?? 0;

        $result = $this->sessionModel
            ->bookSession($student_id, $session_id);

        if ($result["status"]) {
            header(
                "Location: doubt_sessions.php?message=" .
                urlencode($result["message"])
            );
        } else {
            header(
                "Location: doubt_sessions.php?error=" .
                urlencode($result["message"])
            );
        }

        exit();
    }
}
?>
