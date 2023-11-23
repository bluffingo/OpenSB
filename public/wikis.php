<?php

namespace openSB;

global $betty;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/WikiList.php';

$page = new \Orange\Pages\WikiList($betty);

$twig = new Templating($betty);

echo $twig->render('wikis.twig', [
    'data' => $page->getData(),
]);
