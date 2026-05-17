<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../models/Resource.php";

class ResourceController {

    private $resourceModel;

    public function __construct($db) {
        $this->resourceModel = new Resource($db);
    }

    public function materials($student_id) {

        $course_id = $_GET["course_id"] ?? "";

        $courses = $this->resourceModel
            ->enrolledCourses($student_id);

        $materials = $this->resourceModel
            ->getMaterials($student_id, $course_id);

        include __DIR__ . "/../views/student/materials.php";
    }

    public function announcements($student_id) {

        $course_id = $_GET["course_id"] ?? "";

        $courses = $this->resourceModel
            ->enrolledCourses($student_id);

        $announcements = $this->resourceModel
            ->getAnnouncements($student_id, $course_id);

        include __DIR__ . "/../views/student/announcements.php";
    }
}
?>