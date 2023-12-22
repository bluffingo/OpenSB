<?php

namespace openSB;

global $orange, $bettyTemplate;

use \Orange\OrangeException;
use \Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/Index.php';

try {
    $index = new \Orange\Pages\Index($orange);
    $data = $index->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new Templating($orange);

echo $twig->render('index.twig', [
    'data' => $data,
]);
