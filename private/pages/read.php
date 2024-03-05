<?php

namespace OpenSB;

global $orange;

use SquareBracket\SquareBracketException;
use SquareBracket\Pages\JournalRead;
use SquareBracket\Templating;

$id = ($_GET['j'] ?? null);

try {
    $page = new JournalRead($orange, $id);
    $data = $page->getData();
} catch (SquareBracketException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('read.twig', [
    'data' => $data,
]);