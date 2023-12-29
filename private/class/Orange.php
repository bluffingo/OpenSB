<?php
namespace Orange;

use Orange\Database;
use Orange\Utilities;
use Orange\OrangeException;

/**
 * @since Orange 1.0
 */
class Orange {
    private \Orange\Database $database;
    private string $version;
    public array $options;

    public function __construct($host, $user, $pass, $db) {
        $this->makeVersionString();

        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        $this->options = [];
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        }

        // should not be enabled on qobo.tv
        if ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1") {
            $this->options["development"] = true;
        }

        try {
            $this->database = new \Orange\Database($host, $user, $pass, $db);
        } catch (OrangeException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database for other Orange classes to use.
     *
     * @since Orange 1.0
     *
     * @return Database
     */
    public function getDatabase(): \Orange\Database {
        return $this->database;
    }

    /**
     * Make Orange's version number.
     *
     * @since Orange 1.0
     */
    private function makeVersionString()
    {
        // Versioning guide (By Bluffingo, last updated 12/19/2023):
        //
        // * Bump the first number (X.xx) only if a major internal codebase update occurs.
        // * Bump the second number (x.XX) only if it's a feature update, say for Qobo.
        // * We do not have a third number unlike Semantic Versioning or something like Minecraft, since
        // we use Git hashes for indicating revisions, but this may change.
        $version = "1.1";
        $gitPath = __DIR__ . '/../../.git';

        // Check if the instance is git cloned. If it is, have the version string be
        // precise. Otherwise, just indicate that it's a "Non-source copy", though we
        // should find a better term for this. -Bluffingo 12/19/2023
        if(file_exists($gitPath)) {
            $gitHead = file_get_contents($gitPath . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents($gitPath . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $this->version = sprintf('%s.%s-%s', $version, $hash, $gitBranch);
        } else {
            $this->version = sprintf('%s (Non-source copy)', $version);
        }
    }

    /**
     * Returns Orange's version number. Originally named getBettyVersion().
     *
     * @since Orange 1.0
     */
    public function getVersionString(): string
    {
        return $this->version;
    }

    /**
     * Returns the user's local settings.
     *
     * @since Orange 1.0
     *
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->options;
    }

    /**
     * Notifies the user, VidLii-style.
     *
     * Not to be confused with NotifyUser.
     *
     * @since Orange 1.0
     */
    public function Notification($message, $redirect, $color = "danger")
    {
        $_SESSION["notif_message"] = $message;
        $_SESSION["notif_color"] = $color;

        if ($redirect) {
            header(sprintf('Location: %s', $redirect));
            die();
        }
    }
}