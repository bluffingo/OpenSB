<?php

namespace Betty\Pages;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

/**
 * Backend code for the MediaWiki-styled version page.
 *
 * @since 0.1.0
 */
class Version
{
    private $betty;
    private $opensb_version;
    public function __construct(\Betty\Betty $betty, \Betty\OpenSbVersion $opensb_version)
    {
        $this->betty = $betty;
        $this->opensb_version = $opensb_version;
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
            'sbVersion' => array(
                'title' => "openSB version",
                'info' => sprintf("%s on branch %s", $this->opensb_version->getVersion(), $this->opensb_version->getGitBranch()),
            ),
            'bettyVersion' => array(
                'title' => "Betty version",
                'info' => $this->betty->getBettyVersion(),
            ),
        );
    }
}