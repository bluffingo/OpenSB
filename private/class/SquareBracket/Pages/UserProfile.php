<?php

namespace SquareBracket\Pages;

use SquareBracket\Adapter\ActivityPubToSB;
use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\SubmissionData;
use SquareBracket\UnorganizedFunctions;
use WebFinger\WebFinger;

/**
 * Backend code for the profile page.
 *
 * @since SquareBracket 1.0
 */
class UserProfile
{
    private bool $isFediverse;
    private \Core\Database $database;
    private $data;
    private $is_own_profile;
    private array $user_submissions;
    private array $user_journals;
    private CommentData $comments;
    private mixed $followers;
    private $followed;

    public function __construct(\SquareBracket\SquareBracket $orange, $username)
    {
        global $auth, $domain;

        $this->isFediverse = false;

        $activityPubAdapter = new ActivityPubToSB($orange);
        $whereRatings = UnorganizedFunctions::whereRatings();
        $this->database = $orange->getDatabase();

        if (str_contains($username, "@" . $domain)) {
            // if the handle matches our domain then don't treat it as an external fediverse account
            $extractedAddress = explode('@', $username);
            $this->data = $this->database->fetch("SELECT * FROM users u WHERE u.name = ?", [$extractedAddress[0]]);
        } elseif (str_contains($username, "@")) {
            // if the handle contains "@" then check if it's in our db
            $this->isFediverse = true;
            $this->data = $this->database->fetch(
                "SELECT * FROM users u INNER JOIN activitypub_user_urls ON activitypub_user_urls.user_id = u.id WHERE u.name = ?", [$username]);
        } else {
            // otherwise it's a normal opensb account
            $this->data = $this->database->fetch("SELECT * FROM users u WHERE u.name = ?", [$username]);
        }

        //var_dump($this->data);

        if (!$this->data)
        {
            // if we know if it's a fediverse account, then try getting its profile and then copying it over to our
            // database. (TODO: handle blacklisted sites)
            if ($this->isFediverse) {
                $webfinger = new WebFinger($this->database, $username);
                if ($webfinger->requestWebFinger()) {
                    if ($data = $activityPubAdapter->getProfileFromWebFinger($webfinger->getWebFingerData())) {
                        $activityPubAdapter->makeDummySquareBracketAccount($data, $username);
                    }
                } else {
                    UnorganizedFunctions::Notification("This user and/or instance does not exist.", "/");
                }
            } else {
                UnorganizedFunctions::Notification("This user and/or instance does not exist.", "/");
            }
        }

        // shit, how will bans work via fediverse?
        if ($this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$this->data["id"]]))
        {
            UnorganizedFunctions::Notification("This user is banned.", "/");
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
        $this->followed = UnorganizedFunctions::IsFollowingUser($this->data["id"]);
    }

    public function getData(): array
    {
        $data = [
            "id" => $this->data["id"],
            "username" => $this->data["name"],
            "displayname" => $this->data["title"],
            "about" => ($this->data['about'] ?? false),
            "joined" => $this->data["joined"],
            "connected" => $this->data["lastview"],
            "is_current" => $this->is_own_profile,
            "featured_submission" => $this->getSubmissionFromFeaturedID(),
            "submissions" => UnorganizedFunctions::makeSubmissionArray($this->database, $this->user_submissions),
            "journals" => UnorganizedFunctions::makeJournalArray($this->database, $this->user_journals),
            "comments" => $this->comments->getComments(),
            "followers" => $this->followers,
            "following" => $this->followed,
            "is_fedi" => $this->isFediverse,
        ];

        if ($this->isFediverse) {
            $data["fedi_pfp"] = $this->data["profile_picture"];
            $data["fedi_banner"] = $this->data["banner_picture"];
        }

        return $data;
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