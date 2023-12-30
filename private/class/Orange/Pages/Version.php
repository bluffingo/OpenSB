<?php

namespace Orange\Pages;

use Composer\ComposerInstalled;

/**
 * Backend code for the MediaWiki-styled version page.
 *
 * @since Orange 1.0
 */
class Version
{
    private $orange;
    private $database;
    public function __construct(\Orange\Orange $orange)
    {
        $this->orange = $orange;
        $this->database = $orange->getDatabase();
    }

    /**
     * Returns an array containing the product versions, the developer credits, and the Composer packages.
     *
     * @since Orange 1.0
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            "developers" => [
                'Bluffingo',
                'icanttellyou',
                'ROllerozxa',
            ],
            "software" => [
                'orangeVersion' => [
                    'title' => "OpenSB",
                    'info' => $this->orange->getVersionString(),
                ],
                'phpVersion' => [
                    'title' => "PHP",
                    'info' => phpversion(),
                ],
                'dbVersion' => [
                    'title' => "Database software",
                    'info' => $this->database->getVersion(),
                ],
            ],
            "composer_packages" => $this->getComposerPackages(),
        ];
    }

    /**
     * Get a list of all the installed Composer packages. Akin to getExternalLibraries on MediaWiki.
     *
     * @since Orange 1.1
     *
     * @return array
     */
    private function getComposerPackages(): array
    {
        $dependencies = [];

        $installed = new ComposerInstalled(SB_VENDOR_PATH . '/composer/installed.json');

        $dependencies += $installed->getInstalledDependencies();

        ksort($dependencies);

        return $dependencies;
    }
}