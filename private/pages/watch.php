<?php

namespace OpenSB;

global $twig, $database, $auth, $orange;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\SubmissionData;
use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;
use SquareBracket\Utilities;

$id = $path[2] ?? null;

if (isset($_GET['v'])) UnorganizedFunctions::redirect('/submission/' . $_GET['v']);

$submission = new SubmissionData($database, $id);

// check if the submission has been taken down.
$takedown = $submission->getTakedown();
if ($takedown) {
    // go back to homepage with a notification
    UnorganizedFunctions::Notification("This submission has been taken down: " . $takedown["reason"], "/");
}

// todo: check if video is in deleted_videos
$data = $submission->getData();
if (!$data) {
    UnorganizedFunctions::Notification("This submission does not exist.", "/");
}
$comments = new CommentData($database, CommentLocation::Submission, $id);
$author = new UserData($database, $data["author"]);
if ($author->isUserBanned()) {
    UnorganizedFunctions::Notification("The author of this submission is banned.", "/");
}

$followers = $database->result("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$data["author"]]);
$followed = UnorganizedFunctions::IsFollowingUser($data["author"]);

// looks weird, whatever.
$ratings = [
    "1" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$data["id"]]),
    "2" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$data["id"]]),
    "3" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$data["id"]]),
    "4" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$data["id"]]),
    "5" => $database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$data["id"]]),
];
$favorites = $database->result("SELECT COUNT(video_id) FROM favorites WHERE video_id=?", [$id]);

$bools = $submission->bitmaskToArray();

if ($bools["block_guests"] && !$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("This submission's author has blocked guest access.", "/login.php");
}

if (UnorganizedFunctions::RatingToNumber($data["rating"]) > UnorganizedFunctions::RatingToNumber($auth->getUserData()["comfortable_rating"])) {
    UnorganizedFunctions::Notification("This submission is not suitable according to your settings.", "/");
}

$ip = Utilities::get_ip_address();

// I have a feeling that more than half of the views gained in 2023 are non-genuine crawler views.
// even with crawler detect, it doesn't quite work since squarebracket got 240 views on 4/11/2024.
// the best solution would be to check if the ip is from a consumer isp and not from a vps or a search
// engine crawler, but this would most likely require an api that would cost money to use in the long-term.
// i think only counting views from logged-in users would be good for now. -chaziz 4/12/2024
if ($auth->isUserLoggedIn()) {
    $type = "user"; }
else {
    $type = "guest";
}

if ($database->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=? AND user=?", [$id, crypt($ip, $ip)])['COUNT(video_id)'] < 1) {
    $database->query("INSERT INTO views (video_id, user, timestamp, type) VALUES (?,?,?,?)",
        [$id, crypt($ip, $ip), time(), $type]);

    // BUG: if a user views a submission logged out, and then logs onto sb, and then comes back to that submission,
    // the views doesn't count even though it should. -chaziz 4/13/2024
    if ($auth->isUserLoggedIn()) {
        // increment the indexed view count. this might go out of sync eventually, but this can be fixed with a
        // script that'll be run at least once a week via cron. -chaziz 4/6/2024
        $new_views = $data["views"] + 1;
        $database->query("UPDATE videos SET views = ? WHERE id = ?",
            [$new_views, $data["id"]]);
    }
}

$whereRatings = UnorganizedFunctions::whereRatings();
$recommended = $database->fetchArray($database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings AND v.author = ? ORDER BY RAND() LIMIT 24", [$data["author"]]));

if ($auth->getUserID() == $data["author"]) { $owner = true; } else { $owner = false; }

$page_data = [
    "is_owner" => $owner,
    "int_id" => $data["id"],
    "id" => $data["video_id"],
    "title" => $data["title"],
    "description" => $data["description"],
    "published" => $data["time"],
    "original_site" => $data["original_site"],
    "published_originally" => $data["original_time"],
    "type" => $data["post_type"],
    "file" => UnorganizedFunctions::getSubmissionFile($data),
    "author" => [
        "id" => $data["author"],
        "info" => $author->getUserArray(),
        "followers" => $followers,
        "following" => $followed,
    ],
    "interactions" => [
        "views" => $data["views"],
        "ratings" => UnorganizedFunctions::calculateRatings($ratings),
        "favorites" => $favorites,
    ],
    "comments" => $comments->getComments(),
    "bools" => $bools,
    "rating" => $data["rating"],
    "recommended" => UnorganizedFunctions::makeSubmissionArray($database,$recommended),
];

if ($orange->getLocalOptions()["skin"] == "finalium") {
    // calculates the ratio, copied from a late-2021 commit
    function calculateRatio($number, $percent, $total) {
        // If there's no ratio or dislikes, it returns 100.
        if ($total == 0 or $number == 0) {
            return 100;
        } else {
            // It returns the Like-to-dislike ratio.
            return ($percent / $total) * $number * 100;
        }
    }

    if ($auth->isUserLoggedIn()) {
        $current_rating_from_db = $database->result("SELECT rating FROM rating WHERE video=? AND user=?", [$data["id"], $auth->getUserID()]);

        // this is for the like/dislike buttons in the frontend
        if (($current_rating_from_db == "4") || ($current_rating_from_db == "5")) {
            $current_rating = "like";;
        } elseif (($current_rating_from_db == "1") || ($current_rating_from_db == "2")) {
            $current_rating = "dislike";
        } else {
            $current_rating = null;
        }
    } else {
        $current_rating = null;
    }

    // translate 5 stars into like/dislikes. we do this because using the star rating ratio doesn't work that well
    // with the likesaber on finalium. TODO: bring back like/dislike system onto bootstrap
    // -chaziz 6/11/2024
    $likes = $ratings["4"] + $ratings["5"];
    $dislikes = $ratings["1"] + $ratings["2"];
    $total = $likes + $dislikes;

    $page_data["interactions"]["legacy"] = [
        "likes" => $likes,
        "dislikes" => $dislikes,
        "ratio" => calculateRatio($dislikes, $likes, $total),
        "current_rating" => $current_rating,
    ];
}

echo $twig->render('watch.twig', [
    'submission' => $page_data,
]);