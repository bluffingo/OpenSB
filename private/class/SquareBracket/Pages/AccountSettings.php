<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * Backend code for the account settings page.
 *
 * @since SquareBracket 1.0
 */
class AccountSettings
{
    private \SquareBracket\Database $database;
    private \SquareBracket\SubmissionData $submission;
    private mixed $id;
    private \SquareBracket\SquareBracket $orange;
    private mixed $data;

    public function __construct(\SquareBracket\SquareBracket $orange)
    {
        global $auth;

        $this->orange = $orange;
        $this->database = $orange->getDatabase();

        if (!$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
        }

        // we shouldn't let banned users change settings.
        /*
        if ($auth->getUserBanData()) {
            $orange->Notification("You cannot proceed with this action.", "/");
        }
        */
    }

    public function postData($data)
    {
        global $auth, $storage;

        $title = htmlspecialchars($data['title']) ?? null;
        $about = $data['about'] ?? null;

        $resetToken = $data['reset_token'] ?? null;

        $currentPass = ($data['current_pass'] ?? null);
        $pass = ($data['pass'] ?? null);
        $pass2 = ($data['pass2'] ?? null);

        $rating = $data['rating'] ?? "general";

        $error = '';

        if ($currentPass && $pass && $pass2) {
            $password = $this->database->fetch("SELECT password FROM users WHERE id = ?", [$auth->getUserID()])["password"];
            if (password_verify($currentPass, $password)) {
                if ($pass == $pass2) {
                    $this->database->query("UPDATE users SET password = ?, token = ? WHERE id = ?",
                        [password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $auth->getUserID()]);

                    // TODO
                    die("TODO: REDIRECT TO LOGIN PAGE");
                    //redirect('login.php?new_pass');
                } else {
                    $error .= " The new passwords aren't identical.";
                }
            } else {
                $error .= "Your current password is incorrect.";
            }
        }
        if ($error) $error = "The following errors occured while changing your password: " . $error;

        // does this even work???
        if ($resetToken) {
            $this->database->query("UPDATE users SET token = ? WHERE id = ?", [bin2hex(random_bytes(32)), $auth->getUserID()]);
            // TODO
            die("TODO: REDIRECT TO LOGIN PAGE");
            //redirect('login.php?new_pass');
        }

        // banned users shouldn't be able to change their profile
        if (strlen($title) > 50) {
            $error .= "Your display name is too long.";
        }
        if (!empty($_FILES['profilePicture']['name'])) {
            $name = $_FILES['profilePicture']['name'];
            $temp_name = $_FILES['profilePicture']['tmp_name'];
            $ext = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
            $storage->uploadProfilePicture($temp_name, $auth->getUserData()["name"]);
        }

        if (!$error) {
            $this->database->query("UPDATE users SET title = ?, about = ?, comfortable_rating = ? WHERE id = ?",
                [$title, $about, $rating, $auth->getUserID()]);
            UnorganizedFunctions::Notification("Edited successfully!", ("user.php?name=" . $auth->getUserData()["name"]), "success");
        } else {
            UnorganizedFunctions::Notification($error, "/settings.php");
        }
    }
}