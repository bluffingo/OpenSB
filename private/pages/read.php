<?php

namespace Orange;

global $orange;

use \Orange\OrangeException;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/JournalRead.php';

$id = ($_GET['j'] ?? null);

try {
    $page = new \Orange\Pages\JournalRead($orange, $id);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new \Orange\Templating($orange);

echo $twig->render('read.twig', [
    'data' => $data,
]);