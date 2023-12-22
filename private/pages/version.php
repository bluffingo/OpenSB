<?php

namespace Orange;

global $orange, $bettyTemplate, $opensb_version;
require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/Version.php';

$page = new \Orange\Pages\Version($orange);
$twig = new \Orange\Templating($orange);

echo $twig->render('version.twig', [
    'version_stats' => $page->getVersionData(),
    'developers' => $page->getDevelopers(),
]);