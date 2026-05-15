<?php

require_once __DIR__ .
"/../../config/db.php";

require_once __DIR__ .
"/../models/Performance.php";

class PerformanceController {

    private $performanceModel;

    public function __construct($db) {

        $this->performanceModel =
            new Performance($db);
    }

    public function dashboard(
        $student_id
    ) {

        $overall =
            $this->performanceModel
                ->overallStats(
                    $student_id
                );

        $classAverage =
            $this->performanceModel
                ->classAverage();

        $subjects =
            $this->performanceModel
                ->subjectPerformance(
                    $student_id
                );

        $recent =
            $this->performanceModel
                ->recentResults(
                    $student_id
                );

        include __DIR__ .
        "/../views/student/performance.php";
    }
}
?>