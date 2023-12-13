<?php

namespace openSB;

global $betty, $bettyTemplate, $opensb_version;
require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/Version.php';

$page = new \Orange\Pages\Version($betty);
$twig = new \Orange\Templating($betty);

echo $twig->render('version.twig', [
    'version_stats' => $page->getVersionData(),
    'developers' => $page->getDevelopers(),
]);