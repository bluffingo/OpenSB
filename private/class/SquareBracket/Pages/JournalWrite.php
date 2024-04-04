<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * Backend code for the journal writing page.
 *
 * @since SquareBracket 1.0
 */
class JournalWrite
{
    private \Core\Database $database;
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
        global $disableWritingJournals, $auth, $isDebug;

        $this->orange = $orange;
        $this->database = $orange->getDatabase();
        
        if (!$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData()) {
            UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
        }

        if ($disableWritingJournals) {
            UnorganizedFunctions::Notification("The ability to write journals has been disabled.", "/");
        }

        if ($this->database->result("SELECT COUNT(*) FROM journals WHERE date > ? AND author = ?", [time() - 180 , $auth->getUserID()]) && !$isDebug) {
            UnorganizedFunctions::Notification("Please wait three minutes before posting a journal again.", "/");
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

            UnorganizedFunctions::Notification("Your journal has been posted.", "./user.php?name=" . $auth->getUserData()["name"], "success");
    }
}