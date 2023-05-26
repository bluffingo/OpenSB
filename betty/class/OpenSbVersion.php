<?php

namespace Betty;

/**
 * Takes care of openSB versioning stuff for Betty.
 */
class OpenSbVersion
{
    private string $version;
    private string $git_branch;

    /**
     * @param $version string The openSB version number.
     * @param $git_branch string The current git branch.
     */
    public function __construct(string $version, string $git_branch)
    {
        $this->version = $version;
        $this->git_branch = $git_branch;
    }

    /**
     * Return the openSB version number.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Return the current git branch.
     *
     * @return string
     */
    public function getGitBranch(): string
    {
        return $this->git_branch;
    }
}