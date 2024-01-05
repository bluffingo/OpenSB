<?php

namespace OpenSB;

global $orange;

use Orange\OrangeException;
use Orange\Templating;
use Orange\Pages\AdminDashboard;

try {
    $page = new AdminDashboard($orange, $_POST, $_GET);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('admin.twig', [
    'data' => $data
]);