<?php

namespace OpenSB;

global $twig, $database;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\SubmissionQuery;

$submission_query = new SubmissionQuery($database);

function getOrderFromType($type): string
{
    switch ($type) {
        case 'recent':
            $order = "v.time DESC";
            break;
        case 'popular':
            $order = "views DESC";
            break;
        case 'random':
            $order = "RAND()";
            break;
        default:
            $order = "v.time DESC";
            break;
    }
    return $order;
}

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$order = getOrderFromType($type);
$limit = sprintf("%s,%s", (($page_number - 1) * 20), 20);

$whereRatings = UnorganizedFunctions::whereRatings();
$whereTagBlacklist = UnorganizedFunctions::whereTagBlacklist();

$submissions = $submission_query->query($order, $limit);
$submission_count = $submission_query->count();

$data = [
    "submissions" => UnorganizedFunctions::makeSubmissionArray($database, $submissions),
    "count" => $submission_count,
];

echo $twig->render('browse.twig', [
    'data' => $data,
    'page' => $page_number,
    'type' => $type,
]);