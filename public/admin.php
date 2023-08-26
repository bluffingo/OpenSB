<?php

namespace openSB;

global $betty;

use Orange\BettyException;
use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/AdminDashboard.php';

try {
    $page = new \Orange\Pages\AdminDashboard($betty);
    $data = $page->getData();
} catch (BettyException $e) {
    $e->page();
}

$twig = new Templating($betty);

echo $twig->render('admin.twig', [
    'data' => $data
]);