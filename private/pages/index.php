<?php

namespace Orange;

global $orange, $bettyTemplate;

use \Orange\OrangeException;
use \Orange\Templating;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/Index.php';

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
