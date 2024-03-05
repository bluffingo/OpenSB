<?php

namespace OpenSB;

global $orange;

use SquareBracket\SquareBracketException;
use SquareBracket\Pages\SubmissionBrowse;
use SquareBracket\Templating;

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

try {
    $page = new SubmissionBrowse($orange, $type, $page_number);
    $data = $page->getData();
} catch (SquareBracketException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('browse.twig', [
    'data' => $data,
    'page' => $page_number,
]);