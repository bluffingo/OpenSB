<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * Backend code for the submission uploading page.
 *
 * @since SquareBracket 1.0
 */
class SubmissionUpload
{
    private \SquareBracket\Database $database;
    private \SquareBracket\SquareBracket $orange;
    /**
     * @var array|string[]
     */
    private array $supportedVideoFormats;
    /**
     * @var array|string[]
     */
    private array $supportedImageFormats;

    public function __construct(\SquareBracket\SquareBracket $orange)
    {
        global $disableUploading, $auth, $isDebug;

        $this->orange = $orange;
        $this->database = $orange->getDatabase();

        $this->supportedVideoFormats = ["mp4", "mkv", "wmv", "flv", "avi", "mov", "3gp"];
        $this->supportedImageFormats = ["png", "jpg", "jpeg"];

        if (!$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData()) {
            UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
        }

        if ($disableUploading) {
            UnorganizedFunctions::Notification("The ability to upload submissions has been disabled.", "/");
        }

        if (!$auth->isUserAdmin()) {
            // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
            if ($this->database->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 180, $auth->getUserID()]) && !$isDebug) {
                UnorganizedFunctions::Notification("Please wait three minutes before uploading again.", "/");
            }
        }
    }

    public function postData(array $post_data, $files)
    {
        global $storage, $auth, $isDebug;

        $new = UnorganizedFunctions::generateRandomizedString(11, true);
        $uploader = $auth->getUserID();

        $title = ($post_data['title'] ?? null);
        $description = ($post_data['desc'] ?? null);
        if ($isDebug) {
            $noProcess = ($post_data['debugUploaderSkip'] ?? null);
        }

        $name = $files['fileToUpload']['name'];
        $temp_name = $files['fileToUpload']['tmp_name']; // gets upload info
        $ext = pathinfo($files['fileToUpload']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $this->supportedVideoFormats, true)) {
            if (isset($noProcess) && $isDebug) {
                $status = 0x0; // pretend that video has been successfully uploaded
                $target_file = SB_DYNAMIC_PATH . '/dynamic/videos/' . $new . '.converted.' . $ext;
            } else {
                $status = 0x2;
                $target_file = SB_DYNAMIC_PATH . '/videos/' . $new . '.' . $ext;
            }
            if (move_uploaded_file($temp_name, $target_file)) {
                $this->database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, rating) VALUES (?,?,?,?,?,?,?,?,?)",
                    [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $post_data['tags'])), 'dynamic/videos/' . $new, $status, ($post_data["rating"] ?? "general")]);

                if (!isset($noProcess)) {
                    $storage->processVideo($new, $target_file);
                }

                UnorganizedFunctions::Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
            } else {
                UnorganizedFunctions::Notification("There's a problem with permissions and/or PHP File configuration on the server. Please contact the instance staff.", "/upload");
            }
        } elseif (in_array(strtolower($ext), $this->supportedImageFormats, true)) {
            $storage->processImage($temp_name, $new);
            $status = 0x0;
            $this->database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type, rating) VALUES (?,?,?,?,?,?,?,?,?,?)",
                [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $post_data['tags'])), '/dynamic/art/' . $new . '.png', $status, 2, ($post_data["rating"] ?? "general")]);

            UnorganizedFunctions::Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
        } else {
            UnorganizedFunctions::Notification("This file format is not supported.", "/upload");
        }
    }
}