<?php

namespace OpenSB;

global $orange, $bettyTemplate;

use Core\CoreException;
use SquareBracket\Pages\AccountNotifications;
use SquareBracket\Templating;

try {
    $page = new AccountNotifications($orange);
    $data = $page->getData();
} catch (CoreException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('portal.twig', [
    'data' => $data,
]);