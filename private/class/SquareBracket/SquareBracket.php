<?php
namespace SquareBracket;

/**
 * The core SquareBracket class.
 *
 * @since SquareBracket 1.0
 */
class SquareBracket {
    private \SquareBracket\Database $database;
    public array $options;

    /**
     * Initialize core SquareBracket classes.
     *
     * @since SquareBracket 1.0
     */
    public function __construct($host, $user, $pass, $db) {
        global $defaultTemplate;
        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);

            // biscuit frontend is now internally called "biscuit" to avoid any confusion with bitqobo.
            // to avoid a bug where the old userlink implementation is used in squarebrackettwigextension,
            // automatically update SBOPTIONS on the fly.
            if ($this->options["skin"] == "qobo")
            {
                $this->options["skin"] = "biscuit";
                setcookie("SBOPTIONS", base64_encode(json_encode($this->options)), 2147483647);
            }
        } else {
            $this->options = [
                "skin" => $defaultTemplate,
                "theme" => "default",
                "sounds" => false,
            ];
        }

        try {
            $this->database = new \SquareBracket\Database($host, $user, $pass, $db);
        } catch (CoreException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database class for other SquareBracket classes to use.
     *
     * @return Database
     * @since SquareBracket 1.0
     *
     */
    public function getDatabase(): \SquareBracket\Database
    {
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