<?php

namespace OpenSB;

global $orange;

use Core\CoreException;
use SquareBracket\Pages\AdminDashboard;
use SquareBracket\Templating;

try {
    $page = new AdminDashboard($orange, $_POST, $_GET);
    $data = $page->getData();
} catch (CoreException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('admin.twig', [
    'data' => $data
]);