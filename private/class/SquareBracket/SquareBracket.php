<?php
namespace SquareBracket;

/**
 * The core SquareBracket class.
 *
 * @since SquareBracket 1.0
 */
class SquareBracket {
    private Database $database;
    public array $options;
    private array $accounts;
    private string $accounts_cookie_warning = "DO-NOT-SHARE-THIS-WITH-ANYONE-";

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

        if (isset($_COOKIE["SBACCOUNTS"])) {
            $stupid_fucking_bullshit = str_replace($this->accounts_cookie_warning, "", $_COOKIE["SBACCOUNTS"]);
            $this->accounts = json_decode(base64_decode($stupid_fucking_bullshit), true);
        } else {
            $this->accounts = [];
        }

        try {
            $this->database = new Database($host, $user, $pass, $db);
        } catch (CoreException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database class for other SquareBracket classes to use.
     *
     * @return Database
     *
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Returns the user's local settings.
     *
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->options;
    }

    /**
     * Returns warning string for accounts cookie.
     *
     * @return string
     */
    public function getWarningString(): string
    {
        return $this->accounts_cookie_warning;
    }

    /**
     * Returns array for changing accounts.
     *
     * @return string
     */
    public function getAccountsArray()
    {
        return $this->accounts;
    }
}