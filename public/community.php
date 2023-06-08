<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Betty\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/Community.php';

$twig = new Templating($betty, $bettyTemplate);

$page = new \Betty\Pages\Community($betty);
$data = $page->getData();

echo $twig->render('community.twig', [
    'data' => $data,
]);