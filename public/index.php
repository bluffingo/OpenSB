<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Betty\BettyException;
use \Betty\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/Index.php';

try {
    $index = new \Betty\Pages\Index($betty);
    $data = $index->getData();
} catch (BettyException $e) {
    $e->page();
}

$twig = new Templating($betty);

echo $twig->render('index.twig', [
    'data' => $data,
]);
