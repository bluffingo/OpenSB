<?php

namespace openSB;

global $orange;

use Orange\OrangeException;
use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/AdminDashboard.php';

try {
    $page = new \Orange\Pages\AdminDashboard($orange, $_POST, $_GET);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('admin.twig', [
    'data' => $data
]);