<?php

namespace SquareBracket;

use Core\CoreException;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Static utilities.
 *
 * @since SquareBracket 1.0
 */
class Utilities
{
    /**
     * Get the submission's file, works for all three storage modes.
     *
     * @param array|bool $submission The submission data
     * @return array|string|null
     * @since openSB Beta 3.0
     */
    public static function getSubmissionFile(array|bool $submission): array|string|null
    {
        global $isQoboTV, $bunnySettings;
        if ($submission == null)
        {
            return null;
        }

        if ($isQoboTV) {
            if ($submission['post_type'] == 0) {
                // videofile on videos using bunnycdn are the guid, don't ask me why. -grkb 4/8/2023
                return "https://" . $bunnySettings["streamHostname"] . "/" . $submission["videofile"] . "/playlist.m3u8";
            } elseif ($submission['post_type'] == 2) {
                // https://qobo-grkb.b-cdn.net/dynamic/art/f_eKEJNj4bm.png
                return "https://" . $bunnySettings["pullZone"] . $submission["videofile"];
            }
        }
        return $submission['videofile'];
    }

    /**
     * Calculate the submission's ratings.
     *
     * @since openSB Beta 3.0
     */
    public static function calculateRatings($ratings): array
    {
        $total_ratings = ($ratings["1"] +
            $ratings["2"] +
            $ratings["3"] +
            $ratings["4"] +
            $ratings["5"]);

        if($total_ratings == 0) {
            $average_ratings = 0;
        } else {
            $average_ratings = ($ratings["1"] +
                    $ratings["2"] * 2 +
                    $ratings["3"] * 3 +
                    $ratings["4"] * 4 +
                    $ratings["5"] * 5) / $total_ratings;
        }

        return [
            "stars" => $ratings,
            "total" => $total_ratings,
            "average" => $average_ratings,
        ];
    }

    public static function makeSubmissionArray($database, $submissions): array
    {
        $submissionsData = [];
        foreach ($submissions as $submission) {

            $bools = Utilities::submissionBitmaskToArray($submission["flags"]);

            $ratingData = [
                "1" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$submission["id"]]),
                "2" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$submission["id"]]),
                "3" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$submission["id"]]),
                "4" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$submission["id"]]),
                "5" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$submission["id"]]),
            ];

            $views = $database->result("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$submission["video_id"]]);

            $userData = new UserData($database, $submission["author"]);
            $submissionsData[] =
                [
                    "id" => $submission["video_id"],
                    "title" => $submission["title"],
                    "description" => $submission["description"],
                    "published" => $submission["time"],
                    "published_originally" => $submission["original_time"],
                    "original_site" => $submission["original_site"],
                    "type" => $submission["post_type"],
                    "content_rating" => $submission["rating"],
                    "views" => $views,
                    "flags" => $bools,
                    "author" => [
                        "id" => $submission["author"],
                        "info" => $userData->getUserArray(),
                    ],
                    "interactions" => [
                        "ratings" => Utilities::calculateRatings($ratingData),
                    ],
                ];
        }

        return $submissionsData;
    }

    public static function makeJournalArray($database, $journals): array
    {
        $journalsData = [];
        foreach ($journals as $journal) {
            $userData = new UserData($database, $journal["author"]);
            $journalsData[] =
                [
                    "id" => $journal["id"],
                    "title" => $journal["title"],
                    "contents" => $journal["post"],
                    "published" => $journal["date"],
                    "author" => [
                        "id" => $journal["author"],
                        "info" => $userData->getUserArray(),
                    ],
                ];
        }

        return $journalsData;
    }

    public static function whereRatings(): string
    {
        global $auth;

        if ($auth->isUserLoggedIn()) {
            $rating = $auth->getUserData()["comfortable_rating"];

            /*
            $return_value = match ($rating) {
                'general' => 'v.rating = "general"',
                'questionable' => 'v.rating = "general" or v.rating = "questionable"',
                'mature' => 'v.rating = "general" or v.rating = "questionable" or v.rating = "mature"',
            };
            */

            $return_value = match ($rating) {
                'general' => 'v.rating IN ("general")',
                'questionable' => 'v.rating IN ("general","questionable")',
                'mature' => 'v.rating IN ("general","questionable","mature")',
            };
        } else {
            $return_value = 'v.rating IN ("general")';
        }

        return $return_value;
    }

    // TODO: This should probably be an enum class.
    public static function RatingToNumber($rating): int
    {
        return match ($rating) {
            'general' => 0,
            'questionable' => 1,
            'mature' => 2,
        };
    }

    /**
     * Not to be confused with Notification, which makes a banner.
     *
     * @since SquareBracket 1.0
     */
    public static function NotifyUser($database, $user, $submission, $related_id, NotificationEnum $type): void
    {
        global $auth, $database;

        if (!$auth->isUserLoggedIn()) {
            throw new CoreException("NotifyUser should not be called by the backend if current user is logged off.");
        }

        // If this user hasen't been notified by an identical notification the day prior.
        if (!$database->result("SELECT COUNT(*) FROM notifications WHERE timestamp > ? AND type = ? AND recipient = ? AND sender = ?",
                [time() - 86400, $type->value, $user, $auth->getUserID()])) {
            // Notify the user
            $database->query("INSERT INTO notifications (type, level, recipient, sender, timestamp, related_id) VALUES (?,?,?,?,?,?);",
                [$type->value, $submission, $user, $auth->getUserID(), time(), $related_id]);
        }
    }

    public static function IsFollowingUser($user) {
        global $auth, $database;

        return $database->result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$user, $auth->getUserID()]);
    }

    public static function submissionBitmaskToArray($bitmask): array
    {
        return [
            "featured" => (bool)($bitmask & 1),
            "unprocessed" => (bool)($bitmask & 2),
            "block_guests" => (bool)($bitmask & 4),
            "block_comments" => (bool)($bitmask & 8),
            "custom_thumbnail" => (bool)($bitmask & 16),
        ];
    }

    /**
     * Notifies the user, VidLii-style.
     *
     * Not to be confused with NotifyUser.
     *
     * @param $message
     * @param $redirect
     * @param string $color
     * @since SquareBracket 1.0
     */
    public static function Notification($message, $redirect, string $color = "danger"): void
    {
        $_SESSION["notif_message"] = $message;
        $_SESSION["notif_color"] = $color;

        if ($redirect) {
            header(sprintf('Location: %s', $redirect));
            die();
        }
    }

    /**
     * @since SquareBracket 1.1
     */
    public static function processImageSubmissionFile($temp_name, $target): void
    {
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($temp_name);
        $img->scaleDown(2048);
        $img->toPng()->save($target);
    }

    /**
     * @since SquareBracket 1.1
     */
    public static function processImageSubmissionThumbnail($temp_name, $target): void
    {
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($temp_name);
        $img->scaleDown(240); // used to be 500, but 500 was too big when the site displays thumbnails smaller than that.
        $img->toJpeg(90)->save($target);
    }

    /**
     * @since SquareBracket 1.1
     */
    public static function processCustomThumbnail($temp_name, $target) {
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($temp_name);
        $img->scaleDown(1280);
        $img->toJpeg(80)->save($target);
    }

    /**
     * @since SquareBracket 1.1
     */
    public static function processProfilePicture($temp_name, $target): void
    {
        $manager = new ImageManager(Driver::class);
        $img = $manager->read($temp_name);
        // i have to do this otherwise non-1:1 images that are smaller than 512x512 won't be stretched
        $img->resize(512, 512);
        $img->toPng()->save($target);
    }
}