<?php

namespace SquareBracket\Pages;

use Core\CoreException;
use Core\Utilities;
use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\UserData;
use SquareBracket\UnorganizedFunctions;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * Backend code for the submission view (watch) page.
 *
 * @since SquareBracket 1.0
 */
class SubmissionView
{
    private \Core\Database $database;
    private \SquareBracket\SubmissionData $submission;
    private mixed $data;
    private CommentData $comments;
    private array $ratings;
    private mixed $favorites;
    private UserData $author;
    private array $bools;
    private array $recommended;
    private mixed $followers;
    private mixed $followed;

    /**
     * @throws CoreException
     */
    public function __construct(\SquareBracket\SquareBracket $orange, $id)
    {
        global $auth; // honestly i feel like the whole "getBettyDatabase" shit is so redudant -chaziz 8/23/2023

        $CrawlerDetect = new CrawlerDetect;
        $this->database = $orange->getDatabase();
        $this->submission = new \SquareBracket\SubmissionData($this->database, $id);

        // check if the submission has been taken down.
        $takedown = $this->submission->getTakedown();
        if ($takedown) {
            // go back to homepage with a notification
            UnorganizedFunctions::Notification("This submission has been taken down: " . $takedown["reason"], "/");
        }

        $this->data = $this->submission->getData();
        if (!$this->data) {
            UnorganizedFunctions::Notification("This submission does not exist.", "/");
        }
        $this->comments = new CommentData($this->database, CommentLocation::Submission, $id);
        $this->author = new UserData($this->database, $this->data["author"]);
        if ($this->author->isUserBanned()) {
            UnorganizedFunctions::Notification("The author of this submission is banned.", "/");
        }

        $this->followers = $this->database->fetch("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$this->data["author"]])['COUNT(user)'];
        $this->followed = UnorganizedFunctions::IsFollowingUser($this->data["author"]);

        // looks weird, whatever.
        $this->ratings = [
            "1" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$this->data["id"]]),
            "2" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$this->data["id"]]),
            "3" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$this->data["id"]]),
            "4" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$this->data["id"]]),
            "5" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$this->data["id"]]),
        ];
        $this->favorites = $this->database->result("SELECT COUNT(video_id) FROM favorites WHERE video_id=?", [$id]);

        $this->bools = $this->submission->bitmaskToArray();

        if ($this->bools["block_guests"] && !$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("This submission's author has blocked guest access.", "/login.php");
        }

        if (UnorganizedFunctions::RatingToNumber($this->data["rating"]) > UnorganizedFunctions::RatingToNumber($auth->getUserData()["comfortable_rating"])) {
            UnorganizedFunctions::Notification("This submission is not suitable according to your settings.", "/");
        }

        $ip = Utilities::get_ip_address();

        // I have a feeling that more than half of the views gained in 2023 are non-genuine crawler views.
        // even with crawler detect, it doesn't quite work since squarebracket got 240 views on 4/11/2024.
        // the best solution would be to check if the ip is from a consumer isp and not from a vps or a search
        // engine crawler, but this would most likely require an api that would cost money to use in the long-term.
        // i think only counting views from logged-in users would be good for now. -bluff 4/12/2024
        if ($CrawlerDetect->isCrawler()) {
            $type = "crawler";
        } elseif ($auth->isUserLoggedIn()) {
            $type = "user";
        } else {
            $type = "guest";
        }

        if ($this->database->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=? AND user=?", [$id, crypt($ip, $ip)])['COUNT(video_id)'] < 1) {
            $this->database->query("INSERT INTO views (video_id, user, timestamp, type) VALUES (?,?,?,?)",
                [$id, crypt($ip, $ip), time(), $type]);

            if ($auth->isUserLoggedIn()) {
                // increment the indexed view count. this might go out of sync eventually, but this can be fixed with a
                // script that'll be run at least once a week via cron. -bluff 4/6/2024
                $new_views = $this->data["views"] + 1;
                $this->database->query("UPDATE videos SET views = ? WHERE id = ?",
                    [$new_views, $this->data["id"]]);
            }
        }

        $whereRatings = UnorganizedFunctions::whereRatings();
        $this->recommended = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings AND v.author = ? ORDER BY RAND() LIMIT 24", [$this->data["author"]]));
    }

    /**
     * Returns an array containing the submission for the openSB frontend.
     *
     * @since SquareBracket 1.0
     *
     * @return array
     */
    public function getSubmission(): array
    {
        global $auth;
        if ($auth->getUserID() == $this->data["author"]) { $owner = true; } else { $owner = false; }

        return [
            "is_owner" => $owner,
            "int_id" => $this->data["id"],
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "original_site" => $this->data["original_site"],
            "published_originally" => $this->data["original_time"],
            "type" => $this->data["post_type"],
            "file" => UnorganizedFunctions::getSubmissionFile($this->data),
            "author" => [
                "id" => $this->data["author"],
                "info" => $this->author->getUserArray(),
                "followers" => $this->followers,
                "following" => $this->followed,
            ],
            "interactions" => [
                "views" => $this->data["views"],
                "ratings" => UnorganizedFunctions::calculateRatings($this->ratings),
                "favorites" => $this->favorites,
            ],
            "comments" => $this->comments->getComments(),
            "bools" => $this->bools,
            "rating" => $this->data["rating"],
            "recommended" => UnorganizedFunctions::makeSubmissionArray($this->database,$this->recommended),
        ];
    }
}