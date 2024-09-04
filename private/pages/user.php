<?php

namespace OpenSB;

global $auth, $database, $twig;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\UploadData;
use SquareBracket\Utilities;
use SquareBracket\UploadQuery;

$submission_query = new UploadQuery($database);

$username = $path[2] ?? null;

if (isset($_GET['name'])) Utilities::redirect('/user/' . $_GET['name']);

$data = $database->fetch("SELECT * FROM users u WHERE u.name = ?", [$username]);

if (!$data)
{
    // check if this username was used before and was changed out of.
    $old_username_data = $database->fetch("SELECT user FROM user_old_names WHERE old_name = ?", [$username]);

    if ($old_username_data) {
        // if so, redirect to the new profile.
        $new_username = $database->fetch("SELECT name FROM users WHERE id = ?", [$old_username_data['user']])["name"];
        http_response_code(301);
        header("Location: /user/$new_username");
        exit();
    } else {
        Utilities::bannerNotification("This user does not exist.", "/");
    }
}

if ($database->fetch("SELECT * FROM bans WHERE userid = ?", [$data["id"]]))
{
    Utilities::bannerNotification("This user is banned.", "/");
}

$user_submissions = $submission_query->query("v.time desc", 12, "v.author = ?", [$data["id"]]);

$user_journals =
    $database->fetchArray(
        $database->query("SELECT j.* FROM journals j WHERE
                         j.author = ? 
                         ORDER BY j.date 
                         DESC LIMIT 20", [$data["id"]]));

$is_own_profile = ($data["id"] == $auth->getUserID());

if ($is_own_profile || $auth->isUserAdmin()) {
    $old_usernames = $database->fetchArray($database->query("SELECT * FROM user_old_names WHERE user = ?", [$data["id"]]));
} else {
    $old_usernames = [];
}

$comments = new CommentData($database, CommentLocation::Profile, $data["id"]);

$followers = $database->result("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$data["id"]]);
$followed = Utilities::IsFollowingUser($data["id"]);
$views = $database->result("SELECT SUM(views) FROM videos WHERE author = ?", [$data["id"]]);

$profile_data = [
    "id" => $data["id"],
    "username" => $data["name"],
    "displayname" => $data["title"],
    "color" => $data["customcolor"],
    "about" => ($data['about'] ?? false),
    "joined" => $data["joined"],
    "connected" => $data["lastview"],
    "is_current" => $is_own_profile,
    "submissions" => Utilities::makeUploadArray($database, $user_submissions),
    "journals" => Utilities::makeJournalArray($database, $user_journals),
    "comments" => $comments->getComments(),
    "followers" => $followers,
    "following" => $followed,
    "is_staff" => ($data["powerlevel"] > 1),
    "views" => $views,
    "old_usernames" => $old_usernames,
];

// TODO: this should be in the admin panel instead of here.
if ($auth->isUserAdmin()) {
    $staff_notes = $database->fetchArray($database->query("SELECT * FROM user_staff_notes WHERE user = ?", [$data["id"]]));
    $profile_data["notes"] = $staff_notes;
}

echo $twig->render('profile.twig', [
    'data' => $profile_data,
]);