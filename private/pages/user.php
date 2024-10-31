<?php

namespace OpenSB;

global $auth, $database, $twig;

use OpenSB\class\Core\CommentData;
use OpenSB\class\Core\CommentLocation;
use OpenSB\class\Core\UploadData;
use OpenSB\class\Core\ProfileLayoutEnum;
use OpenSB\class\Core\Utilities;
use OpenSB\class\Core\UploadQuery;

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

if ($database->fetch("SELECT * FROM user_bans WHERE userid = ?", [$data["id"]]))
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

// placeholder
$profile_color_data = [
    "font" => '"Comic Sans MS", "Comic Sans", cursive;',
    // https://www.youtube.com/watch?v=MldpN-L2nbc
    "yt2010_background_color" => "#CCCCCC",
    "yt2010_wrapper_color" => "#999999",
    "yt2010_wrapper_text_color" => "#000000",
    "yt2010_wrapper_link_color" => "#0000CC",
    "yt2010_wrapper_opacity" => "100",
    "yt2010_box_background_color" => "#EEEEFF",
    "yt2010_title_text_color" => "#000000",
    "yt2010_body_text_color" => "#333333",
    "yt2010_box_opacity" => "100",
    // https://www.youtube.com/watch?v=sraD_cyNQN4
    "yt2008_background_color" => "#FFFFFF",
    "yt2008_link_color" => "#0033CC",
    "yt2008_label_color" => "#666666",
    "yt2008_opacity" => "95", // pretty sure the default was 95%
    "yt2008_basic_box_border_color" => "#666666",
    "yt2008_basic_box_background_color" => "#FFFFFF",
    "yt2008_basic_box_text_color" => "#000000",
    "yt2008_highlight_box_background_color" => "#E6E6E6",
    "yt2008_highlight_box_text_color" => "#666666",
];

$template = "profile.twig";
$page_name = "user";

if ($orange->getLocalOptions()["skin"] == "charla") {
    switch (ProfileLayoutEnum::from($data["profile_layout"])) {
        //case ProfileLayoutEnum::Default:
        //    $template = "profile.twig";
        //    $page_name = "user";
        //    break;

        case ProfileLayoutEnum::Default:
            break;

        case ProfileLayoutEnum::YtChannel2008:
            $template = "profile_yt2008.twig";
            $page_name = "profile-yt2008";
            break;

        case ProfileLayoutEnum::YtChannel2010:
            $template = "profile_yt2010.twig";
            $page_name = "profile-yt2010";
            break;
    }
}

$comments = new CommentData($database, CommentLocation::Profile, $data["id"]);

$followers = $database->result("SELECT COUNT(user) FROM user_follows WHERE id = ?", [$data["id"]]);
$followed = Utilities::IsFollowingUser($data["id"]);
$views = $database->result("SELECT SUM(views) FROM uploads WHERE author = ?", [$data["id"]]);

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
    "customization" => $profile_color_data,
];

echo $twig->render($template, [
    'data' => $profile_data,
    'page_name' => $page_name,
]);