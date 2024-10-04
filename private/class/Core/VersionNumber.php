<?php

namespace OpenSB\class\Core;

class VersionNumber
{
    private string $version;

    public function __construct() {
        $this->makeVersionString();
    }

    /**
     * Make SquareBracket's version number.
     */
    private function makeVersionString(): void
    {
        if (file_exists(SB_GIT_PATH)) {
            $gitHead = file_get_contents(SB_GIT_PATH . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents(SB_GIT_PATH . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $versionNumber = "2.0-alpha";

            $this->version = sprintf('%s.%s-%s', $versionNumber, $gitBranch, $hash);
        } else {
            $this->version = 'Unknown';
        }
    }

    public function printVersionForOutput()
    {
        return sprintf("OpenSB %s - Executed at %s", VersionNumber::getVersionString(), date("Y-m-d h:i:s")) . PHP_EOL;
    }

    /**
     * Returns SquareBracket's version number. Originally named getBettyVersion().
     *
     * @return string
     */
    public function getVersionString(): string
    {
        return $this->version;
    }
}