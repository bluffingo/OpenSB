<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\UnorganizedFunctions;

function getOrderFromType($type): string
{
    switch ($type) {
        case 'recent':
            $order = "v.time";
            break;
        case 'popular':
            $order = "views";
            break;
        case 'discussed':
            $order = "comments"; // BROKEN
            break;
        case 'favorited':
            $order = "favorites"; // BROKEN
            break;
        case 'random':
            $order = "RAND()";
            break;
        default:
            $order = "v.time";
            break;
    }
    return $order;
}

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$order = getOrderFromType($type);
$limit = sprintf("LIMIT %s,%s", (($page_number - 1) * 20), 20);

$whereRatings = UnorganizedFunctions::whereRatings();

$database = $orange->getDatabase();
$submissions = $database->fetchArray($database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY $order DESC $limit"));
$submission_count = $database->result("SELECT COUNT(*) FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings");

$data = [
    "submissions" => UnorganizedFunctions::makeSubmissionArray($database, $submissions),
    "count" => $submission_count,
];

echo $twig->render('browse.twig', [
    'data' => $data,
    'page' => $page_number,
    'type' => $type,
]);