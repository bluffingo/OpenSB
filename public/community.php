<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/Community.php';

$twig = new Templating($betty, $bettyTemplate);

$page = new \Orange\Pages\Community($betty);
$data = $page->getData();

echo $twig->render('community.twig', [
    'data' => $data,
]);