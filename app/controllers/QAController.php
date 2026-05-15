<?php

require_once __DIR__ .
"/../../config/db.php";

require_once __DIR__ .
"/../models/QA.php";

class QAController {

    private $qaModel;

    public function __construct($db) {

        $this->qaModel =
            new QA($db);
    }

    public function board() {

        $questions =
            $this->qaModel
                ->getQuestions();

        include __DIR__ .
        "/../views/student/qa_board.php";
    }

    public function ask(
        $student_id
    ) {

        $message = "";

        $courses =
            $this->qaModel
                ->getCourses(
                    $student_id
                );

        if (
            $_SERVER["REQUEST_METHOD"]
            === "POST"
        ) {

            $course_id =
                $_POST["course_id"];

            $title =
                trim($_POST["title"]);

            $body =
                trim($_POST["body"]);

            $this->qaModel
                ->askQuestion(
                    $course_id,
                    $student_id,
                    $title,
                    $body
                );

            $message =
                "Question posted successfully.";
        }

        include __DIR__ .
        "/../views/student/ask_question.php";
    }

    public function answerView(
        $student_id
    ) {

        $question_id =
            $_GET["id"] ?? 0;

        if (
            $_SERVER["REQUEST_METHOD"]
            === "POST"
        ) {

            $body =
                trim($_POST["body"]);

            $this->qaModel
                ->addAnswer(
                    $question_id,
                    $student_id,
                    $body
                );
        }

        $question =
            $this->qaModel
                ->questionDetails(
                    $question_id
                );

        $answers =
            $this->qaModel
                ->getAnswers(
                    $question_id
                );

        include __DIR__ .
        "/../views/student/answer_view.php";
    }

    public function resolve() {

        $question_id =
            $_GET["id"] ?? 0;

        $this->qaModel
            ->resolveQuestion(
                $question_id
            );

        header(
            "Location: qa_board.php"
        );

        exit();
    }
}
?>