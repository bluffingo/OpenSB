<?php
namespace SquareBracket;

use Core\CoreException;
use Core\Database;
use Core\VersionNumber;

/**
 * The core SquareBracket class.
 *
 * @since SquareBracket 1.0
 */
class SquareBracket {
    private \Core\Database $database;
    private \SquareBracket\SiteSettings $settings;
    public array $options;

    /**
     * Initialize core SquareBracket classes.
     *
     * @since SquareBracket 1.0
     */
    public function __construct($host, $user, $pass, $db) {
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