<?php

namespace Orange;

/**
 * Takes care of openSB versioning stuff for Betty.
 *
 * @since 0.1.0
 */
class OpenSbVersion
{
    private string $version;
    private string $git_branch;

    /**
     * @param $version string The openSB version number.
     * @param $git_branch string The current git branch.
     *
     * @since 0.1.0
     */
    public function __construct(string $version, string $git_branch)
    {
        $this->version = $version;
        $this->git_branch = $git_branch;
    }

    /**
     * Return the openSB version number.
     *
     * @since 0.1.0
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
     * @since 0.1.0
     *
     * @return string
     */
    public function getGitBranch(): string
    {
        return $this->git_branch;
    }
}