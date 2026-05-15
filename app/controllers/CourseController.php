<?php

require_once __DIR__ .
"/../../config/db.php";

require_once __DIR__ .
"/../models/Course.php";

class CourseController {

    private $courseModel;

    public function __construct($db) {

        $this->courseModel =
            new Course($db);
    }

    public function courses(
        $student_id
    ) {

        $search =
            $_GET["search"] ?? "";

        $subject_id =
            $_GET["subject_id"] ?? "";

        $subjects =
            $this->courseModel
                ->getSubjects();

        $courses =
            $this->courseModel
                ->getCourses(
                    $student_id,
                    $search,
                    $subject_id
                );

        include __DIR__ .
        "/../views/student/courses.php";
    }

    public function enroll(
        $student_id
    ) {

        $course_id =
            $_GET["course_id"] ?? 0;

        $this->courseModel
            ->enroll(
                $student_id,
                $course_id
            );

        header(
            "Location: courses.php"
        );

        exit();
    }

    public function details(
        $student_id
    ) {

        $course_id =
            $_GET["id"] ?? 0;

        $course =
            $this->courseModel
                ->courseDetails(
                    $student_id,
                    $course_id
                );

        include __DIR__ .
        "/../views/student/course_details.php";
    }

    public function drop(
        $student_id
    ) {

        $course_id =
            $_GET["course_id"] ?? 0;

        $this->courseModel
            ->dropCourse(
                $student_id,
                $course_id
            );

        header(
            "Location: dashboard.php"
        );

        exit();
    }
}
?>