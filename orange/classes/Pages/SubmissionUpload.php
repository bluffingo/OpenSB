<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\BettyException;
use Orange\CommentLocation;
use Orange\Comments;
use Orange\Database;
use Orange\SubmissionData;

/**
 * Backend code for the submission modification page.
 *
 * @since 0.1.0
 */
class SubmissionUpload
{
    private \Orange\Database $database;
    private \Orange\Orange $orange;
    /**
     * @var array|string[]
     */
    private array $supportedVideoFormats;
    /**
     * @var array|string[]
     */
    private array $supportedImageFormats;

    public function __construct(\Orange\Orange $betty)
    {
        global $disableUploading, $auth, $isDebug;

        $this->orange = $betty;
        $this->database = $betty->getBettyDatabase();

        $this->supportedVideoFormats = ["mp4", "mkv", "wmv", "flv", "avi", "mov", "3gp"];
        $this->supportedImageFormats = ["png", "jpg", "jpeg"];
        
        if (!$auth->isUserLoggedIn())
        {
            $betty->Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData()) {
            $betty->Notification("You cannot proceed with this action.", "/");
        }

        if ($disableUploading) {
            $betty->Notification("The ability to upload submissions has been disabled.", "/");
        }

        // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
        if ($this->database->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 180 , $auth->getUserID()]) && !$isDebug) {
            $this->orange->Notification("Please wait three minutes before uploading again.", "/");
        }
    }

    public function postData(array $post_data, $files)
    {
        global $storage, $auth, $isDebug;

        $uploader = $auth->getUserID();
        $new = $this->orange->randomString(11);

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
                $target_file = dirname(__DIR__) . '/../../dynamic/videos/' . $new . '.converted.' . $ext;
            } else {
                $status = 0x2;
                $target_file = dirname(__DIR__) . '/../../dynamic/videos/' . $new . '.' . $ext;
            }
            if (move_uploaded_file($temp_name, $target_file)) {
                $this->database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags) VALUES (?,?,?,?,?,?,?,?)",
                    [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $post_data['tags'])), 'dynamic/videos/' . $new, $status]);

                if (!isset($noProcess)) {
                    $storage->processVideo($new, $target_file);
                }

                $this->orange->Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
            }
        } elseif (in_array(strtolower($ext), $this->supportedImageFormats, true)) {
            $storage->processImage($temp_name, $new);
            $status = 0x0;
            $this->database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type) VALUES (?,?,?,?,?,?,?,?,?)",
                [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $post_data['tags'])), '/dynamic/art/' . $new . '.png', $status, 2]);

            $this->orange->Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
        } else {
            $this->orange->Notification("This file format is not supported.", "/");
        }
    }
}