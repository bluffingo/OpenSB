<?php

namespace OpenSB;

global $twig, $orange;

use Core\CoreException;
use SquareBracket\Pages\AccountSubmissions;
use SquareBracket\Templating;

$type = ($_GET['type'] ?? 'recent');
$page_number = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

try {
    $page = new AccountSubmissions($orange, $type, $page_number);
    $data = $page->getData();
} catch (CoreException $e) {
    $e->page();
}

echo $twig->render('my_submissions.twig', [
    'data' => $data,
    'page' => $page_number,
]);