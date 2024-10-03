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
     *
     */
    private function makeVersionString(): void
    {
        if (file_exists(SB_GIT_PATH)) {
            $gitHead = file_get_contents(SB_GIT_PATH . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents(SB_GIT_PATH . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $this->version = sprintf('%s-%s', $hash, $gitBranch);
        } else {
            $this->version = 'Unknown';
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
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

            return sprintf("OpenSB %s; %s", VersionNumber::getVersionString(), $domain);
        } else {
            return "printVersionForUserAgent() shouldn't be used in this context";
        }
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