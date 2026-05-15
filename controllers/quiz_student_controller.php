<?php

require_once __DIR__ .
"/../config/db.php";

require_once __DIR__ .
"/../models/QuizStudentModel.php";

class QuizController {

    private $quizModel;

    public function __construct($db) {

        $this->quizModel =
            new QuizStudentModel($db);
    }

    public function quizzes(
        $student_id
    ) {

        $quizzes =
            $this->quizModel
                ->getQuizzes(
                    $student_id
                );

        include __DIR__ .
        "/../views/student/quizzes.php";
    }

    public function takeQuiz() {

        $quiz_id =
            $_GET["quiz_id"] ?? 0;

        $quiz =
            $this->quizModel
                ->getQuiz($quiz_id);

        $questions =
            $this->quizModel
                ->getQuestions($quiz_id);

        include __DIR__ .
        "/../views/student/take_quiz.php";
    }

    public function submitQuiz(
        $student_id
    ) {

        $quiz_id =
            $_POST["quiz_id"];

        $answers =
            $_POST["answers"] ?? [];

        $score = 0;

        $attempt_id =
            $this->quizModel
                ->saveAttempt(
                    $quiz_id,
                    $student_id,
                    0
                );

        foreach (
            $answers
            as $question_id => $option_id
        ) {

            $this->quizModel
                ->saveAnswer(
                    $attempt_id,
                    $question_id,
                    $option_id
                );

            $correct =
                $this->quizModel
                    ->checkCorrect(
                        $question_id,
                        $option_id
                    );

            if ($correct) {
                $score +=
                    $correct["marks"];
            }
        }

        $stmt = $GLOBALS["conn"]->prepare("
            UPDATE attempts
            SET score = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ii",
            $score,
            $attempt_id
        );

        $stmt->execute();

        header(
            "Location: result.php?attempt_id=" .
            $attempt_id
        );

        exit();
    }

    public function result(
        $student_id
    ) {

        $attempt_id =
            $_GET["attempt_id"] ?? 0;

        $result =
            $this->quizModel
                ->getResult(
                    $attempt_id,
                    $student_id
                );

        include __DIR__ .
        "/../views/student/result.php";
    }

    public function history(
        $student_id
    ) {

        $attempts =
            $this->quizModel
                ->attemptHistory(
                    $student_id
                );

        include __DIR__ .
        "/../views/student/attempt_history.php";
    }
}
?>
