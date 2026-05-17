<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../models/Student.php";

class StudentController {
    private $studentModel;

    public function __construct($db) {
        $this->studentModel = new Student($db);
    }

    public function dashboard($student_id) {
        $stats = $this->studentModel->getDashboardStats($student_id);
        $courses = $this->studentModel->getDashboardCourses($student_id);

        include __DIR__ . "/../views/student/dashboard.php";
    }
}
?>