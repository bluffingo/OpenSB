<?php

namespace OpenSB;

global $twig, $database, $orange, $auth;

use SquareBracket\UnorganizedFunctions;

$whereRatings = UnorganizedFunctions::whereRatings();

$submissions = $database->fetchArray($database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY RAND() LIMIT 24"));
$submissions_recent = $database->fetchArray($database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY v.time DESC LIMIT 24"));
$news_recent = $database->fetchArray($database->query("SELECT j.* FROM journals j WHERE j.is_site_news = 1 ORDER BY j.date DESC LIMIT 3"));

$data = [
    "submissions" => UnorganizedFunctions::makeSubmissionArray($database, $submissions),
    "submissions_new" => UnorganizedFunctions::makeSubmissionArray($database, $submissions_recent),
    "news_recent" => UnorganizedFunctions::makeJournalArray($database, $news_recent),
];

// on the homepage on the finalium layout, when logged in, it shows stats
// (they were actually broken for a long time LOL). this isn't on biscuit
// (yet) or bootstrap. -chaziz 6/11/2024
if ($auth->isUserLoggedIn()) {
    $followers = $database->result("SELECT COUNT(user) FROM subscriptions WHERE id = ?", [$auth->getUserID()]);
    $views = $database->result("SELECT SUM(views) FROM videos WHERE author = ?", [$auth->getUserID()]);

    $data["totals"] = [
        "followers" => $followers,
        "views" => $views,
    ];
}

echo $twig->render('index.twig', [
    'data' => $data,
]);
