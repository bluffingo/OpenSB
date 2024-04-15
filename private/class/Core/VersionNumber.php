<?php

namespace Core;

class VersionNumber
{
    private string $version;

    public function __construct() {
        $this->makeVersionString();
    }

    /**
     * Make SquareBracket's version number.
     *
     * @since SquareBracket 1.0
     */
    private function makeVersionString(): void
    {
        // Check if the instance is git cloned. If it is, have the version string be
        // precise. Otherwise, just indicate that it's a "Non-source copy", though we
        // should find a better term for this. -Chaziz 12/19/2023
        if (file_exists(SB_GIT_PATH)) {
            $gitHead = file_get_contents(SB_GIT_PATH . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents(SB_GIT_PATH . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $this->version = sprintf('%s-%s', $hash, $gitBranch);
        } else {
            $this->version = sprintf('Unknown');
        }
    }

    public function printVersionForOutput()
    {
        return sprintf("OpenSB %s - Executed at %s", VersionNumber::getVersionString(), date("Y-m-d h:i:s")) . PHP_EOL;
    }

    public function printVersionForUserAgent()
    {
        // This user agent will be used for server-to-server communication, especially around the fediverse.
        // For example, The user agent for Akkoma-based instances goes something like this:
        // Software Version-GitHash; Website <hostmaster@website.social>
        // OpenSB will use that, but without a hostmaster address at the end.
        $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

        return sprintf("OpenSB %s; %s", VersionNumber::getVersionString(), $domain);
    }

    /**
     * Returns SquareBracket's version number. Originally named getBettyVersion().
     *
     * @return string
     * @since SquareBracket 1.0
     */
    public function getVersionString(): string
    {
        return $this->version;
    }
}