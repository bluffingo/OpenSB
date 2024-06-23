<?php

namespace OpenSB;

global $twig, $database, $orange, $auth;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\UploadQuery;

$submission_query = new UploadQuery($database);

if ($orange->getLocalOptions()["skin"] == "biscuit") {
    $submissions_random_query_limit = 24;
    $submissions_recent_query_limit = 12;
} else {
    $submissions_random_query_limit = 12;
    $submissions_recent_query_limit = 12;
}

$submissions_random = $submission_query->query("RAND()", $submissions_random_query_limit);
$submissions_recent = $submission_query->query("v.time DESC", $submissions_recent_query_limit);

$news_recent = $database->fetchArray($database->query("SELECT j.* FROM journals j WHERE j.is_site_news = 1 ORDER BY j.date DESC LIMIT 3"));
$tags_recent = $database->fetchArray($database->query("SELECT t.*, COUNT(t.tag_id) AS use_count FROM tag_index ct LEFT JOIN tag_meta t ON ct.tag_id = t.tag_id GROUP BY ct.tag_id ORDER BY latestUse DESC LIMIT 75"));
$users_recent = $database->fetchArray($database->query("SELECT u.id, u.about, u.title, (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num, (SELECT COUNT(*) FROM journals WHERE author = u.id) AS j_num FROM users u ORDER BY u.lastview DESC LIMIT 8"));

$data = [
    "submissions" => UnorganizedFunctions::makeSubmissionArray($database, $submissions_random),
    "submissions_new" => UnorganizedFunctions::makeSubmissionArray($database, $submissions_recent),
    "news_recent" => UnorganizedFunctions::makeJournalArray($database, $news_recent),
    "tags_recent" => $tags_recent, // TODO: makeTagsArray
    "users_recent" => $users_recent, // TODO: makeUsersArray
];

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
