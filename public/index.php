<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Orange\OrangeException;
use \Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/Index.php';

try {
    $index = new \Orange\Pages\Index($betty);
    $data = $index->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($betty);

echo $twig->render('index.twig', [
    'data' => $data,
]);
