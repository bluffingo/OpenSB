<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\AccountSubmissions;

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$page = new AccountSubmissions($orange, $type, $page_number);
$data = $page->getData();

echo $twig->render('my_submissions.twig', [
    'data' => $data,
    'page' => $page_number,
]);