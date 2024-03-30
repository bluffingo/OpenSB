<?php

namespace OpenSB;

global $orange;

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

$twig = new Templating($orange);

echo $twig->render('read.twig', [
    'data' => $data,
]);