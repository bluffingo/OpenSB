<?php

namespace openSB;

global $versionNumber, $betty, $betty_version, $bettyTemplate;
require_once dirname(__DIR__) . '/private/class/common.php';

$version_stats = array(
    'phpVersion' => array(
        'title' => "PHP version",
        'info' => phpversion(),
    ),
    'sbVersion' => array(
        'title' => "openSB version",
        'info' => $versionNumber,
    ),
    'bettyVersion' => array(
        'title' => "Betty version",
        'info' => $betty_version,
    ),
);

$twig = new \Betty\Templating($betty, $bettyTemplate);

echo $twig->render('version.twig', [
    'version_stats' => $version_stats,
]);