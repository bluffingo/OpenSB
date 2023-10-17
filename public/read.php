<?php

namespace openSB;

global $betty;

use \Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/JournalRead.php';

$id = ($_GET['j'] ?? null);

try {
    $page = new \Orange\Pages\JournalRead($betty, $id);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new \Orange\Templating($betty);

echo $twig->render('read.twig', [
    'data' => $data,
]);