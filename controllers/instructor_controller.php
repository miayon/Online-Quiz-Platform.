<?php
// controllers/instructor_controller.php
session_start();

require_once __DIR__ . "/../models/InstructorModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "instructor") {
    header("Location: ../login.php");
    exit();
}

$instructorId = (int)$_SESSION["user_id"];
$action = $_POST["action"] ?? $_GET["action"] ?? "";

function set_flash($message, $type = "success") {
    $_SESSION["flash"] = ["message" => $message, "type" => $type];
}

function redirect_view($view, $query = "") {
    $url = "../views/" . $view . ".php";
    if ($query !== "") {
        $url .= "?" . $query;
    }
    header("Location: " . $url);
    exit();
}

function require_course_owner($instructorId, $courseId) {
    if (!InstructorModel::ownsCourse($instructorId, $courseId)) {
        die("Unauthorized course access.");
    }
}

function require_quiz_owner($instructorId, $quizId) {
    if (!InstructorModel::ownsQuiz($instructorId, $quizId)) {
        die("Unauthorized quiz access.");
    }
}

function upload_instructor_file($inputName) {
    if (empty($_FILES[$inputName]["name"])) {
        return null;
    }

    $allowed = ["pdf", "doc", "docx", "ppt", "pptx", "txt", "jpg", "jpeg", "png", "mp4"];
    $ext = strtolower(pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
        throw new Exception("Invalid file type.");
    }

    if ($_FILES[$inputName]["size"] > 10 * 1024 * 1024) {
        throw new Exception("File size must be less than 10 MB.");
    }

    $dir = __DIR__ . "/../uploads/";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $safe = time() . "_" . preg_replace("/[^A-Za-z0-9._-]/", "_", $_FILES[$inputName]["name"]);
    $target = $dir . $safe;

    if (!move_uploaded_file($_FILES[$inputName]["tmp_name"], $target)) {
        throw new Exception("File upload failed.");
    }

    return "uploads/" . $safe;
}

try {
    if ($action === "update_profile") {
        $name = trim($_POST["name"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $department = trim($_POST["department"] ?? "");
        $bio = trim($_POST["bio"] ?? "");
        $profilePic = upload_instructor_file("profile_pic");

        if ($name === "") {
            throw new Exception("Name is required.");
        }

        InstructorModel::updateProfile($instructorId, $name, $phone, $department, $bio, $profilePic);
        $_SESSION["name"] = $name;

        set_flash("Profile updated successfully.");
        redirect_view("instructor_profile");
    }

    if ($action === "create_course") {
        $subjectId = (int)($_POST["subject_id"] ?? 0);
        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $enrollmentType = $_POST["enrollment_type"] ?? "open";
        $maxStudents = (int)($_POST["max_students"] ?? 50);
        $status = $_POST["status"] ?? "draft";

        if ($title === "" || $subjectId <= 0 || $maxStudents <= 0) {
            throw new Exception("Please fill all required course fields.");
        }

        InstructorModel::createCourse($instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status);
        log_action("Instructor created course", $title);

        set_flash("Course created successfully.");
        redirect_view("instructor_courses");
    }

    if ($action === "update_course") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        $subjectId = (int)($_POST["subject_id"] ?? 0);
        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $enrollmentType = $_POST["enrollment_type"] ?? "open";
        $maxStudents = (int)($_POST["max_students"] ?? 50);
        $status = $_POST["status"] ?? "draft";

        if ($title === "" || $subjectId <= 0 || $maxStudents <= 0) {
            throw new Exception("Please fill all required course fields.");
        }

        InstructorModel::updateCourse($courseId, $instructorId, $subjectId, $title, $description, $enrollmentType, $maxStudents, $status);

        set_flash("Course updated successfully.");
        redirect_view("instructor_course_detail", "course_id=" . $courseId);
    }

    if ($action === "archive_course") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        InstructorModel::archiveCourse($courseId, $instructorId);

        set_flash("Course archived successfully.");
        redirect_view("instructor_courses");
    }

    if ($action === "handle_enrollment") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $enrollmentId = (int)($_POST["enrollment_id"] ?? 0);
        $status = $_POST["status"] ?? "pending";

        require_course_owner($instructorId, $courseId);

        if (!in_array($status, ["active", "dropped"], true)) {
            throw new Exception("Invalid enrollment action.");
        }

        InstructorModel::updateEnrollmentStatus($enrollmentId, $courseId, $status);

        set_flash("Enrollment request updated.");
        redirect_view("instructor_enrollments", "course_id=" . $courseId);
    }

    if ($action === "assign_ta") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $taId = (int)($_POST["ta_id"] ?? 0);

        require_course_owner($instructorId, $courseId);

        InstructorModel::assignTA($courseId, $taId);

        set_flash("TA assigned successfully.");
        redirect_view("instructor_assign_ta", "course_id=" . $courseId);
    }

    if ($action === "remove_ta") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $taId = (int)($_POST["ta_id"] ?? 0);

        require_course_owner($instructorId, $courseId);

        InstructorModel::removeTA($courseId, $taId);

        set_flash("TA removed successfully.");
        redirect_view("instructor_assign_ta", "course_id=" . $courseId);
    }

    if ($action === "create_quiz") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $timeLimit = (int)($_POST["time_limit_minutes"] ?? 0);
        $totalMarks = (int)($_POST["total_marks"] ?? 0);
        $passMark = (int)($_POST["pass_mark"] ?? 0);
        $quizType = $_POST["quiz_type"] ?? "graded";
        $status = $_POST["status"] ?? "draft";
        $availableFrom = $_POST["available_from"] ?: null;
        $availableUntil = $_POST["available_until"] ?: null;

        if ($title === "" || $timeLimit <= 0 || $totalMarks <= 0) {
            throw new Exception("Please fill quiz fields correctly.");
        }

        InstructorModel::createQuiz($courseId, $instructorId, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil);

        set_flash("Quiz created successfully.");
        redirect_view("instructor_quizzes", "course_id=" . $courseId);
    }

    if ($action === "update_quiz") {
        $quizId = (int)($_POST["quiz_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);

        require_quiz_owner($instructorId, $quizId);

        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $timeLimit = (int)($_POST["time_limit_minutes"] ?? 0);
        $totalMarks = (int)($_POST["total_marks"] ?? 0);
        $passMark = (int)($_POST["pass_mark"] ?? 0);
        $quizType = $_POST["quiz_type"] ?? "graded";
        $status = $_POST["status"] ?? "draft";
        $availableFrom = $_POST["available_from"] ?: null;
        $availableUntil = $_POST["available_until"] ?: null;

        InstructorModel::updateQuiz($quizId, $instructorId, $title, $description, $timeLimit, $totalMarks, $passMark, $quizType, $status, $availableFrom, $availableUntil);

        set_flash("Quiz updated successfully.");
        redirect_view("instructor_quizzes", "course_id=" . $courseId);
    }

    if ($action === "toggle_quiz_status") {
        $quizId = (int)($_POST["quiz_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);
        $status = $_POST["status"] ?? "draft";

        require_quiz_owner($instructorId, $quizId);

        InstructorModel::toggleQuizStatus($quizId, $instructorId, $status);

        set_flash("Quiz status updated.");
        redirect_view("instructor_quizzes", "course_id=" . $courseId);
    }

    if ($action === "add_question") {
        $quizId = (int)($_POST["quiz_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);

        require_quiz_owner($instructorId, $quizId);

        $questionText = trim($_POST["question_text"] ?? "");
        $marks = (int)($_POST["marks"] ?? 1);
        $orderIndex = (int)($_POST["order_index"] ?? 1);
        $correctOption = (int)($_POST["correct_option"] ?? 1);
        $options = [
            trim($_POST["option1"] ?? ""),
            trim($_POST["option2"] ?? ""),
            trim($_POST["option3"] ?? ""),
            trim($_POST["option4"] ?? "")
        ];

        if ($questionText === "" || in_array("", $options, true)) {
            throw new Exception("Question and all four options are required.");
        }

        InstructorModel::addQuestion($quizId, $instructorId, $questionText, $marks, $orderIndex, $options, $correctOption);

        set_flash("Question added successfully.");
        redirect_view("instructor_questions", "course_id=" . $courseId);
    }

    if ($action === "edit_question") {
        $questionId = (int)($_POST["question_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);

        $questionText = trim($_POST["question_text"] ?? "");
        $marks = (int)($_POST["marks"] ?? 1);
        $orderIndex = (int)($_POST["order_index"] ?? 1);
        $correctOption = (int)($_POST["correct_option"] ?? 1);
        $options = [
            trim($_POST["option1"] ?? ""),
            trim($_POST["option2"] ?? ""),
            trim($_POST["option3"] ?? ""),
            trim($_POST["option4"] ?? "")
        ];

        if ($questionText === "" || in_array("", $options, true)) {
            throw new Exception("Question and all four options are required.");
        }

        InstructorModel::updateQuestion($questionId, $instructorId, $questionText, $marks, $orderIndex, $options, $correctOption);

        set_flash("Question updated successfully.");
        redirect_view("instructor_questions", "course_id=" . $courseId);
    }

    if ($action === "delete_question") {
        $questionId = (int)($_POST["question_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);

        InstructorModel::deleteQuestion($questionId, $instructorId);

        set_flash("Question deleted successfully.");
        redirect_view("instructor_questions", "course_id=" . $courseId);
    }

    if ($action === "reuse_question") {
        $sourceQuestionId = (int)($_POST["source_question_id"] ?? 0);
        $targetQuizId = (int)($_POST["target_quiz_id"] ?? 0);
        $courseId = (int)($_POST["course_id"] ?? 0);

        InstructorModel::reuseQuestion($sourceQuestionId, $targetQuizId, $instructorId);

        set_flash("Question reused in selected quiz.");
        redirect_view("instructor_questions", "course_id=" . $courseId);
    }

    if ($action === "post_announcement") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        $title = trim($_POST["title"] ?? "");
        $body = trim($_POST["body"] ?? "");

        if ($title === "" || $body === "") {
            throw new Exception("Title and body are required.");
        }

        InstructorModel::postAnnouncement($courseId, $instructorId, $title, $body);

        set_flash("Announcement posted successfully.");
        redirect_view("instructor_announcements", "course_id=" . $courseId);
    }

    if ($action === "upload_material") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        $title = trim($_POST["title"] ?? "");
        $materialType = $_POST["material_type"] ?? "document";
        $filePath = $materialType === "link" ? trim($_POST["external_link"] ?? "") : upload_instructor_file("material_file");

        if ($title === "" || !$filePath) {
            throw new Exception("Please provide material title and file/link.");
        }

        InstructorModel::addMaterial($courseId, $instructorId, $title, $filePath, $materialType);

        set_flash("Material uploaded successfully.");
        redirect_view("instructor_materials", "course_id=" . $courseId);
    }

    if ($action === "edit_material") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $materialId = (int)($_POST["material_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        $title = trim($_POST["title"] ?? "");
        $materialType = $_POST["material_type"] ?? "document";
        $oldPath = trim($_POST["old_file_path"] ?? "");
        $newPath = $materialType === "link" ? trim($_POST["external_link"] ?? "") : upload_instructor_file("material_file");

        if (!$newPath) {
            $newPath = $oldPath;
        }

        InstructorModel::updateMaterial($materialId, $courseId, $instructorId, $title, $newPath, $materialType);

        set_flash("Material updated successfully.");
        redirect_view("instructor_materials", "course_id=" . $courseId);
    }

    if ($action === "delete_material") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $materialId = (int)($_POST["material_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        InstructorModel::deleteMaterial($materialId, $courseId, $instructorId);

        set_flash("Material deleted successfully.");
        redirect_view("instructor_materials", "course_id=" . $courseId);
    }

    if ($action === "answer_qa") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $qaQuestionId = (int)($_POST["qa_question_id"] ?? 0);
        $body = trim($_POST["body"] ?? "");
        require_course_owner($instructorId, $courseId);

        if ($body === "") {
            throw new Exception("Answer cannot be empty.");
        }

        InstructorModel::answerQA($qaQuestionId, $instructorId, $body);

        set_flash("Answer posted successfully.");
        redirect_view("instructor_qa", "course_id=" . $courseId);
    }

    if ($action === "endorse_answer") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $answerId = (int)($_POST["answer_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        InstructorModel::endorseAnswer($answerId, $courseId);

        set_flash("Answer endorsed.");
        redirect_view("instructor_qa", "course_id=" . $courseId);
    }

    if ($action === "resolve_qa") {
        $courseId = (int)($_POST["course_id"] ?? 0);
        $qaQuestionId = (int)($_POST["qa_question_id"] ?? 0);
        require_course_owner($instructorId, $courseId);

        InstructorModel::resolveQuestion($qaQuestionId, $courseId);

        set_flash("Q&A question resolved.");
        redirect_view("instructor_qa", "course_id=" . $courseId);
    }

    redirect_view("instructor_dashboard");
} catch (Throwable $e) {
    set_flash($e->getMessage(), "danger");
    header("Location: " . ($_SERVER["HTTP_REFERER"] ?? "../views/instructor_dashboard.php"));
    exit();
}
?>
