<?php

namespace OpenSB;

global $auth, $domain, $enableFederatedStuff, $activityPubAdapter, $database, $twig;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\SubmissionData;
use SquareBracket\UnorganizedFunctions;

$username = $path[2] ?? null;

if (isset($_GET['name'])) UnorganizedFunctions::redirect('/user/' . $_GET['name']);

if ($enableFederatedStuff) {
    // TODO: following and followers
    if (str_contains($_SERVER['HTTP_ACCEPT'], 'application/ld+json') ||
        str_contains($_SERVER['HTTP_ACCEPT'], 'application/activity+json')) {
        require(SB_PRIVATE_PATH . '/pages/activitypub/user.php');
        die();
    } elseif (str_contains($_SERVER['REQUEST_URI'], '/inbox')) {
        require(SB_PRIVATE_PATH . '/pages/activitypub/inbox.php');
        die();
    }
}

function getSubmissionFromFeaturedID($database, $data)
{
    global $auth;

    // featured_submission, replaces the unused "lastpost" column in the users table.

    // if user hasn't specified anything, then use latest submission, if that doesn't exist, do not bother.
    if ($data["featured_submission"] == 0) {
        $featured_id = $database->fetch(
            "SELECT video_id FROM videos v WHERE v.author = ? ORDER BY v.time DESC", [$data["id"]]);
        if(!isset($featured_id["video_id"])) {
            return false;
        }
        $data["featured_submission"] = $featured_id["video_id"];
        if ($data["featured_submission"] == 0) {
            return false;
        }
    }

    $submission = new SubmissionData($database, $data["featured_submission"]);
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
        || ($data["author"] != $data["id"])
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

$isFediverse = false;
$whereRatings = UnorganizedFunctions::whereRatings();

$instance = null;
if (str_contains($username, "@" . $domain) && $enableFederatedStuff) {
    // if the handle matches our domain then don't treat it as an external fediverse account
    $extractedAddress = explode('@', $username);
    $data = $database->fetch("SELECT * FROM users u WHERE u.name = ?", [$extractedAddress[0]]);
} elseif (str_contains($username, "@") && $enableFederatedStuff) {
    // if the handle contains "@" then check if it's in our db
    $isFediverse = true;
    $extractedAddress = explode('@', $username);
    $instance = $extractedAddress[1];
    $data = $database->fetch(
        "SELECT * FROM users u INNER JOIN activitypub_user_urls ON activitypub_user_urls.user_id = u.id WHERE u.name = ?", [$username]);
} else {
    // otherwise it's a normal opensb account
    $data = $database->fetch("SELECT * FROM users u WHERE u.name = ?", [$username]);
}

if (!$data)
{
    // if we know if it's a fediverse account, then try getting its profile and then copying it over to our
    // database. (TODO: handle blacklisted sites)
    if ($isFediverse) {
        if (!$activityPubAdapter->getFediProfileFromWebFinger($username)) {
            UnorganizedFunctions::Notification("This user and/or instance does not exist.", "/");
        }
    } else {
        UnorganizedFunctions::Notification("This user does not exist.", "/");
    }
}

// shit, how will bans work via fediverse?
if ($database->fetch("SELECT * FROM bans WHERE userid = ?", [$data["id"]]))
{
    UnorganizedFunctions::Notification("This user is banned.", "/");
}

$user_submissions =
    $database->fetchArray(
        $database->query("SELECT v.* FROM videos v WHERE v.video_id 
                                   NOT IN (SELECT submission FROM takedowns) 
                           AND v.author = ?
                           AND $whereRatings 
                         ORDER BY v.time 
                         DESC LIMIT 12", [$data["id"]]));

$user_journals =
    $database->fetchArray(
        $database->query("SELECT j.* FROM journals j WHERE
                         j.author = ? 
                         ORDER BY j.date 
                         DESC LIMIT 3", [$data["id"]]));

$is_own_profile = ($data["id"] == $auth->getUserID());

$comments = new CommentData($database, CommentLocation::Profile, $data["id"]);

$followers = $database->result("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$data["id"]]);
$followed = UnorganizedFunctions::IsFollowingUser($data["id"]);
$views = $database->result("SELECT SUM(views) FROM videos WHERE author = ?", [$data["id"]]);

$profile_data = [
    "id" => $data["id"],
    "username" => $data["name"],
    "displayname" => $data["title"],
    "about" => ($data['about'] ?? false),
    "joined" => $data["joined"],
    "connected" => $data["lastview"],
    "is_current" => $is_own_profile,
    "featured_submission" => getSubmissionFromFeaturedID($database, $data),
    "submissions" => UnorganizedFunctions::makeSubmissionArray($database, $user_submissions),
    "journals" => UnorganizedFunctions::makeJournalArray($database, $user_journals),
    "comments" => $comments->getComments(),
    "followers" => $followers,
    "following" => $followed,
    "is_fedi" => $isFediverse,
    "views" => $views,
];

if ($isFediverse) {
    $profile_data["instance"] = $instance;

    if (isset($data["profile_picture"])) {
        $profile_data["fedi_pfp"] = $data["profile_picture"];
    } else {
        $profile_data["fedi_pfp"] = "/assets/profiledef.png";
    }

    if (isset($data["banner_picture"])) {
        $profile_data["fedi_banner"] = $data["banner_picture"];
    } else {
        $profile_data["fedi_banner"] = "/assets/biscuit_banner.png";
    }
}

echo $twig->render('profile.twig', [
    'data' => $profile_data,
]);