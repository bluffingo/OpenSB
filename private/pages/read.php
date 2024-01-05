<?php

namespace OpenSB;

global $orange;

use Orange\OrangeException;
use Orange\Templating;
use Orange\Pages\JournalRead;

$id = ($_GET['j'] ?? null);

try {
    $page = new JournalRead($orange, $id);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('read.twig', [
    'data' => $data,
]);