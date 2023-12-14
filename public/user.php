<?php

namespace openSB;

global $betty;

use Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/Profile.php';

$id = ($_GET['name'] ?? null);

try {
    $page = new \Orange\Pages\Profile($betty, $id);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new \Orange\Templating($betty);

echo $twig->render('profile.twig', [
    'data' => $data,
]);