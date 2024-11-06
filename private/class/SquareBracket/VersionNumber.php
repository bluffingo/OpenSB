<?php

namespace SquareBracket;

class VersionNumber
{
    private string $versionNumber;
    private string $versionString;

    public function __construct() {
        $this->versionNumber = "1.2.6";
        $this->versionString = $this->makeVersionString();
    }

    /**
     * Make SquareBracket's version number.
     *
     */
    private function makeVersionString(): string
    {
        if (file_exists(SB_GIT_PATH)) {
            $gitHead = file_get_contents(SB_GIT_PATH . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents(SB_GIT_PATH . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            return sprintf('%s.%s-%s', $this->versionNumber, $gitBranch, $hash);
        } else {
            return $this->versionNumber;
        }
    }

    public function printVersionForOutput()
    {
        return sprintf("OpenSB %s - Executed at %s", VersionNumber::getVersionString(), date("Y-m-d h:i:s")) . PHP_EOL;
    }

    /**
     * Returns the version number.
     *
     * @return string
     */
    public function getVersionNumber(): string
    {
        return $this->versionNumber;
    }

    /**
     * Returns the version string.
     *
     * @return string
     */
    public function getVersionString(): string
    {
        return $this->versionString;
    }
}