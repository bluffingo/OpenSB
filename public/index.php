<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Betty\BettyException;
use \Betty\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/pages/Index.php';

try {
    $index = new \Betty\Pages\Index($betty);
    $submissions = $index->getData();
} catch (BettyException $e) {
    $e->page();
}

$twig = new Templating($betty, $bettyTemplate);

echo $twig->render('index.twig', [
    'submissions' => $submissions,
    'updated' => isset($_GET['updated']),
]);
