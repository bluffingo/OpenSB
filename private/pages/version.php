<?php

namespace OpenSB;

global $twig, $database;

use Composer\ComposerInstalled;

/**
 * Get a list of all the installed Composer packages. Akin to getExternalLibraries on MediaWiki.
 *
 * @since SquareBracket 1.1
 *
 * @return array
 */
function getComposerPackages(): array
{
    $dependencies = [];

    $installed = new ComposerInstalled(SB_VENDOR_PATH . '/composer/installed.json');

    $dependencies += $installed->getInstalledDependencies();

    ksort($dependencies);

    return $dependencies;
}

$data = [
    "developers" => [
        'Chaziz',
        'icanttellyou',
        'ROllerozxa',
    ],
    "software" => [
        'orangeVersion' => [
            'title' => "OpenSB",
            'info' => (new \Core\VersionNumber)->getVersionString(),
        ],
        'phpVersion' => [
            'title' => "PHP",
            'info' => phpversion(),
        ],
        'dbVersion' => [
            'title' => "Database software",
            'info' => $database->getVersion(),
        ],
    ],
    "composer_packages" => getComposerPackages(),
];

echo $twig->render('version.twig', [
    'data' => $data,
]);