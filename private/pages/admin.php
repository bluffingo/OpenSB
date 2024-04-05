<?php

namespace OpenSB;

global $twig, $orange;

use Core\CoreException;
use SquareBracket\Pages\AdminDashboard;
use SquareBracket\Templating;

try {
    $page = new AdminDashboard($orange, $_POST, $_GET);
    $data = $page->getData();
} catch (CoreException $e) {
    $e->page();
}

echo $twig->render('admin.twig', [
    'data' => $data
]);