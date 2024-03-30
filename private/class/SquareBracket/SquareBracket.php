<?php
namespace SquareBracket;

use Core\Database;
use Core\CoreException;

/**
 * The core SquareBracket class.
 *
 * @since SquareBracket 1.0
 */
class SquareBracket {
    private \Core\Database $database;
    private \SquareBracket\SiteSettings $settings;
    private string $version;
    public array $options;


    /**
     * Initialize core SquareBracket classes.
     *
     * @since SquareBracket 1.0
     */
    public function __construct($host, $user, $pass, $db) {
        $this->makeVersionString();

        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        $this->options = [];
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        }

        try {
            $this->database = new \Core\Database($host, $user, $pass, $db);
            $this->settings = new \SquareBracket\SiteSettings($this->database);
        } catch (CoreException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database class for other SquareBracket classes to use.
     *
     * @since SquareBracket 1.0
     *
     * @return Database
     */
    public function getDatabase(): \Core\Database {
        return $this->database;
    }

    /**
     * Returns the site settings class for other SquareBracket classes to use.
     *
     * @since SquareBracket 1.1
     *
     * @return SiteSettings
     */
    public function getSettings(): \SquareBracket\SiteSettings {
        return $this->settings;
    }

    /**
     * Make SquareBracket's version number.
     *
     * @since SquareBracket 1.0
     */
    private function makeVersionString()
    {
        // Versioning guide (By Bluffingo, last updated 1/5/2024):
        //
        // * Don't bump the first number unless if the codebase is rewritten.
        // * Bump the second number for every new release.
        // * We do not have a third number unlike Semantic Versioning, since
        // we use Git hashes for indicating revisions, but this may change.
        // * Pre-release versions not ready for Qobo production should be marked "x.x-dev"
        // * Pre-release versions ready for Qobo production should be marked "x.x-RCx", with every
        // (non-bugfix) update to production being a new release candidate version.
        $version = "1.1-RC2";

        // Check if the instance is git cloned. If it is, have the version string be
        // precise. Otherwise, just indicate that it's a "Non-source copy", though we
        // should find a better term for this. -Bluffingo 12/19/2023
        if(file_exists(SB_GIT_PATH)) {
            $gitHead = file_get_contents(SB_GIT_PATH . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents(SB_GIT_PATH . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $this->version = sprintf('%s.%s-%s', $version, $hash, $gitBranch);
        } else {
            $this->version = sprintf('%s (Non-source copy)', $version);
        }
    }

    /**
     * Returns SquareBracket's version number. Originally named getBettyVersion().
     *
     * @since SquareBracket 1.0
     */
    public function getVersionString(): string
    {
        return $this->version;
    }

    public function printVersionForOutput()
    {
        return sprintf("OpenSB %s - Executed at %s", $this->getVersionString(), date("Y-m-d h:i:s")) . PHP_EOL;
    }

    /**
     * Returns the user's local settings.
     *
     * @since SquareBracket 1.0
     *
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->options;
    }
}