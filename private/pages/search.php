<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\SubmissionSearch;

$query = $_GET['query'] ?? null;
$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$page = new SubmissionSearch($orange, $type, $page_number, $query);
$data = $page->getData();

echo $twig->render('browse.twig', [
    'data' => $data,
    'page' => $page_number,
]);