<?php

namespace OpenSB;

global $twig, $orange;

use Core\CoreException;
use SquareBracket\Pages\JournalRead;
use SquareBracket\Templating;

$id = ($_GET['j'] ?? null);

try {
    $page = new JournalRead($orange, $id);
    $data = $page->getData();
} catch (CoreException $e) {
    $e->page();
}

echo $twig->render('read.twig', [
    'data' => $data,
]);