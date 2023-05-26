<?php

namespace Betty\Pages;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

class Version
{
    private $betty;
    private $opensb_version;
    public function __construct(\Betty\Betty $betty, \Betty\OpenSbVersion $opensb_version)
    {
        $this->betty = $betty;
        $this->opensb_version = $opensb_version;
    }

    public function getVersionData()
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