<?php

namespace Orange;

/**
 * Miscellaneous functions that need to be reused.
 *
 * @since 0.1.0
 */
class MiscFunctions
{
    /**
     * Get the submission's file, works for both Qobo mode and local mode.
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

    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") return Null;
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function redirect($url)
    {
        header(sprintf('Location: %s', $url));
        die();
    }

    public static function makeSubmissionArray($database, $submissions): array
    {
        $submissionsData = [];
        foreach ($submissions as $submission) {

            $ratingData = [
                "1" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$submission["id"]]),
                "2" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$submission["id"]]),
                "3" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$submission["id"]]),
                "4" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$submission["id"]]),
                "5" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$submission["id"]]),
            ];

            $userData = new User($database, $submission["author"]);
            $submissionsData[] =
                [
                    "id" => $submission["video_id"],
                    "title" => $submission["title"],
                    "description" => $submission["description"],
                    "published" => $submission["time"],
                    "type" => $submission["post_type"],
                    "content_rating" => $submission["rating"],
                    "author" => [
                        "id" => $submission["author"],
                        "info" => $userData->getUserArray(),
                    ],
                    "interactions" => [
                        "ratings" => MiscFunctions::calculateRatings($ratingData),
                    ],
                ];
        }

        return $submissionsData;
    }

    public static function makeJournalArray($database, $journals): array
    {
        $journalsData = [];
        foreach ($journals as $journal) {
            $userData = new User($database, $journal["author"]);
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

    public static function whereRatings() {
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
}