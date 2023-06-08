<?php

namespace openSB;

global $betty, $bettyTemplate, $opensb_version;
require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/Version.php';

$page = new \Betty\Pages\Version($betty, $opensb_version);
$twig = new \Betty\Templating($betty);

echo $twig->render('version.twig', [
    'version_stats' => $page->getVersionData(),
]);