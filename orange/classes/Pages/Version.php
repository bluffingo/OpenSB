<?php

namespace Orange\Pages;

use Orange\User;
use Orange\BettyException;
use Orange\Database;

/**
 * Backend code for the MediaWiki-styled version page.
 *
 * @since 0.1.0
 */
class Version
{
    private $betty;
    private $database;
    public function __construct(\Orange\Orange $betty)
    {
        $this->betty = $betty;
        $this->database = $betty->getBettyDatabase();
    }

    /**
     * Returns an array containing the versions for the openSB frontend.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getVersionData(): array
    {
        return array(
            'phpVersion' => array(
                'title' => "PHP version",
                'info' => phpversion(),
            ),
            'dbVersion' => array(
                'title' => "Database version",
                'info' => $this->database->getVersion(),
            ),
            'bettyVersion' => array(
                'title' => "openSB version",
                'info' => $this->betty->getBettyVersion(),
            ),
        );
    }
}