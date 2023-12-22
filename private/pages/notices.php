<?php

namespace Orange;

global $orange, $bettyTemplate;

use \Orange\OrangeException;
use \Orange\Templating;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/AccountNotifications.php';

try {
    $page = new \Orange\Pages\AccountNotifications($orange);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('portal.twig', [
    'data' => $data,
]);