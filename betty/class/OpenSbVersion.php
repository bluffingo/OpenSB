<?php

namespace Betty;

/**
 * Takes care of openSB versioning stuff for Betty.
 */
class OpenSbVersion
{
    private $version;
    private $git_branch;

    public function __construct($version, $git_branch)
    {
        $this->version = $version;
        $this->git_branch = $git_branch;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getGitBranch()
    {
        return $this->git_branch;
    }
}