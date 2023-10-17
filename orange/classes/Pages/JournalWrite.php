<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\OrangeException;
use Orange\CommentLocation;
use Orange\Comments;
use Orange\Database;
use Orange\SubmissionData;

/**
 * Backend code for the submission modification page.
 *
 * @since 0.1.0
 */
class JournalWrite
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
        
        if (!$auth->isUserLoggedIn())
        {
            $betty->Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData()) {
            $betty->Notification("You cannot proceed with this action.", "/");
        }

        if ($disableUploading) {
            $betty->Notification("The ability to write journals has been disabled.", "/");
        }

        // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
        if ($this->database->result("SELECT COUNT(*) FROM journals WHERE date > ? AND author = ?", [time() - 180 , $auth->getUserID()]) && !$isDebug) {
            $this->orange->Notification("Please wait three minutes before uploading a journal again.", "/");
        }
    }

    public function postData(array $post_data, $files)
    {
        global $auth;

        $uploader = $auth->getUserID();

        $title = ($post_data['title'] ?? null);
        $description = ($post_data['desc'] ?? null);

        $this->database->query("INSERT INTO journals (title, post, author, date) VALUES (?,?,?,?)",
            [$title, $description, $uploader, time()]);

            $this->orange->Notification("Your journal has been uploaded.", "./user.php?name=" . $auth->getUserData()["name"], "success");
    }
}