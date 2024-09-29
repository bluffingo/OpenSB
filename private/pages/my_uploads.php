<?php

namespace OpenSB;

global $twig, $orange, $auth;

use SquareBracket\Utilities;

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

if (!$auth->isUserLoggedIn())
{
    Utilities::bannerNotification("Please login to continue.", "/login");
}

$limit = sprintf("LIMIT %s,%s", (($page_number - 1) * 20), 20);

$database = $orange->getDatabase();
$submissions = $database->fetchArray($database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND v.author = ? ORDER BY v.id DESC $limit", [$auth->getUserID()]));
$submission_count = $database->result("SELECT COUNT(*) FROM videos where videos.author = ?", [$auth->getUserID()]);

$data = [
    "submissions" => Utilities::makeUploadArray($database, $submissions),
    "count" => $submission_count,
];

echo $twig->render('my_submissions.twig', [
    'data' => $data,
    'page' => $page_number,
]);