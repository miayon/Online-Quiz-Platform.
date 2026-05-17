<?php

require_once __DIR__ . "/../config/db.php";

require_once __DIR__ . "/../models/ProfileModel.php";

class ProfileController {

    private $profileModel;

    public function __construct($db) {

        $this->profileModel =
            new ProfileModel($db);
    }

    public function profile($student_id) {

        $message = "";
        $error = "";

        $user =
            $this->profileModel
                ->getStudent($student_id);

        // UPDATE PROFILE

        if (isset($_POST["update_profile"])) {

            $name = trim($_POST["name"]);
            $phone = trim($_POST["phone"]);
            $program = trim($_POST["program"]);

            $profile_pic =
                $user["profile_pic"];

            if (
                !empty(
                    $_FILES["profile_pic"]["name"]
                )
            ) {

                $image_name =
                    time() .
                    "_" .
                    basename(
                        $_FILES["profile_pic"]["name"]
                    );

                $target =
                    __DIR__ .
                    "/../../public/uploads/" .
                    $image_name;

                move_uploaded_file(
                    $_FILES["profile_pic"]["tmp_name"],
                    $target
                );

                $profile_pic = $image_name;
            }

            $updated =
                $this->profileModel
                    ->updateProfile(
                        $student_id,
                        $name,
                        $phone,
                        $program,
                        $profile_pic
                    );

            if ($updated) {

                $_SESSION["name"] = $name;

                $message =
                    "Profile updated successfully.";

                $user =
                    $this->profileModel
                        ->getStudent($student_id);

            } else {

                $error =
                    "Failed to update profile.";
            }
        }

        // CHANGE PASSWORD

        if (isset($_POST["change_password"])) {

            $old_password =
                $_POST["old_password"];

            $new_password =
                $_POST["new_password"];

            $confirm_password =
                $_POST["confirm_password"];

            if (
                !password_verify(
                    $old_password,
                    $user["password_hash"]
                )
            ) {

                $error =
                    "Old password is incorrect.";

            } elseif (
                $new_password !==
                $confirm_password
            ) {

                $error =
                    "Passwords do not match.";

            } elseif (
                strlen($new_password) < 6
            ) {

                $error =
                    "Password minimum 6 characters.";

            } else {

                $hash =
                    password_hash(
                        $new_password,
                        PASSWORD_DEFAULT
                    );

                $changed =
                    $this->profileModel
                        ->updatePassword(
                            $student_id,
                            $hash
                        );

                if ($changed) {

                    $message =
                        "Password changed successfully.";

                } else {

                    $error =
                        "Password update failed.";
                }
            }
        }

        include __DIR__ .
            "/../views/student/profile.php";
    }
}
?>
