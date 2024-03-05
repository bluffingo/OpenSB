<?php

namespace SquareBracket\Pages;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\SubmissionData;
use SquareBracket\Utilities;

/**
 * Backend code for the profile page.
 *
 * @since SquareBracket 1.0
 */
class UserProfile
{
    private \Orange\Database $database;
    private $data;
    private $is_own_profile;
    private array $user_submissions;
    private array $user_journals;
    private CommentData $comments;
    private mixed $followers;
    private $followed;

    public function __construct(\SquareBracket\SquareBracket $orange, $username)
    {
        global $auth;

        $whereRatings = Utilities::whereRatings();

        $this->database = $orange->getDatabase();
        $this->data = $this->database->fetch("SELECT u.* FROM users u WHERE u.name = ?", [$username]);

        if (!$this->data)
        {
            Utilities::Notification("This user does not exist.", "/");
        }

        if ($this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$this->data["id"]]))
        {
            Utilities::Notification("This user is banned.", "/");
        }

        $this->user_submissions =
            $this->database->fetchArray(
                $this->database->query("SELECT v.* FROM videos v WHERE v.video_id 
                                   NOT IN (SELECT submission FROM takedowns) 
                           AND v.author = ?
                           AND $whereRatings 
                         ORDER BY v.time 
                         DESC LIMIT 12", [$this->data["id"]]));

        $this->user_journals =
            $this->database->fetchArray(
                $this->database->query("SELECT j.* FROM journals j WHERE
                         j.author = ? 
                         ORDER BY j.date 
                         DESC LIMIT 3", [$this->data["id"]]));

        if ($this->data["id"] == $auth->getUserID())
        {
            $this->is_own_profile = true;
        }

        $this->comments = new CommentData($this->database, CommentLocation::Profile, $this->data["id"]);

        $this->followers = $this->database->fetch("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$this->data["id"]])['COUNT(user)'];
        $this->followed = Utilities::IsFollowingUser($this->data["id"]);
    }

    public function getData(): array
    {
        return [
            "id" => $this->data["id"],
            "username" => $this->data["name"],
            "displayname" => $this->data["title"],
            "about" => ($this->data['about'] ?? false),
            "joined" => $this->data["joined"],
            "connected" => $this->data["lastview"],
            "is_current" => $this->is_own_profile,
            "featured_submission" => $this->getSubmissionFromFeaturedID(),
            "submissions" => Utilities::makeSubmissionArray($this->database, $this->user_submissions),
            "journals" => Utilities::makeJournalArray($this->database, $this->user_journals),
            "comments" => $this->comments->getComments(),
            "followers" => $this->followers,
            "following" => $this->followed,
        ];
    }

    private function getSubmissionFromFeaturedID()
    {
        global $auth;

        // featured_submission, replaces the unused "lastpost" column in the users table.

        // if user hasn't specified anything, then use latest submission, if that doesn't exist, do not bother.
        if ($this->data["featured_submission"] == 0) {
            $featured_id = $this->database->fetch(
                "SELECT video_id FROM videos v WHERE v.author = ? ORDER BY v.time DESC", [$this->data["id"]]);
            if(!isset($featured_id["video_id"])) {
                return false;
            }
            $this->data["featured_submission"] = $featured_id["video_id"];
            if ($this->data["featured_submission"] == 0) {
                return false;
            }
        }

        $submission = new SubmissionData($this->database, $this->data["featured_submission"]);
        $data = $submission->getData();
        $bools = $submission->bitmaskToArray();

        // IF:
        // * The submission is taken down, and/or
        // * The submission no longer exists and/or
        // * The submission's author is not the user whose profile we're looking at and/or
        // * The submission is not available to guests and the user isn't signed in and/or
        // * TODO: The submission is privated...
        // then simply just return false, so we don't show the featured submission.
        if (
            $submission->getTakedown()
            || !$data
            || ($data["author"] != $this->data["id"])
            || ($bools["block_guests"] && !$auth->isUserLoggedIn())
        )
        {
            return false;
        } else {
            return [
                "title" => $data["title"],
                "id" => $data["video_id"],
                "type" => $data["post_type"],
            ];
        }
    }
}