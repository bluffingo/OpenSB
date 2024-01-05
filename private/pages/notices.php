<?php

namespace OpenSB;

global $orange, $bettyTemplate;

use Orange\OrangeException;
use Orange\Templating;
use Orange\Pages\AccountNotifications;

try {
    $page = new AccountNotifications($orange);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('portal.twig', [
    'data' => $data,
]);