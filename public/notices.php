<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Orange\OrangeException;
use \Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/Notices.php';

try {
    $page = new \Orange\Pages\Notices($betty);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($betty);

echo $twig->render('portal.twig', [
    'data' => $data,
]);