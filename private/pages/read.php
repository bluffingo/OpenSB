<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\JournalRead;

$id = ($_GET['j'] ?? null);

$page = new JournalRead($orange, $id);
$data = $page->getData();

echo $twig->render('read.twig', [
    'data' => $data,
]);