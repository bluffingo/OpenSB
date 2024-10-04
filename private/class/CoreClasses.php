<?php
namespace OpenSB\class;

use OpenSB\class\Core\Authentication;
use OpenSB\class\Core\CoreException;
use OpenSB\class\Core\Database;
use OpenSB\class\Core\Templating;
use OpenSB\class\Core\Utilities;

/**
 * The core classes.
 */
class CoreClasses {
    private Database $database;
    private Authentication $authentication;
    private Templating $templating;
    // TODO: this should be moved to a separate class
    public array $options;
    // TODO: these should be moved to the Authentication class
    private array $accounts;
    private string $accounts_cookie_warning = "DO-NOT-SHARE-THIS-WITH-ANYONE-";

    /**
     * Initialize core classes.
     *
     */
    public function __construct($config) {
        // TODO: this should be moved to a separate class
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);

            // the finalium 2/biscuit frontend is now internally called "biscuit" instead of "qobo".
            // to avoid a bug where the old userlink implementation is used in squarebrackettwigextension,
            // automatically update SBOPTIONS on the fly.
            if ($this->options["skin"] == "qobo")
            {
                $this->options["skin"] = "biscuit";
                setcookie("SBOPTIONS", base64_encode(json_encode($this->options)), 2147483647);
            }
        } else {
            // NOTE: dont add any more default options.

            $defaultSkin = "biscuit"; // NOTE: biscuit is deprecated but charla isn't shipped by default
            if ($isChazizSB && !Utilities::isChazizTestInstance()) {
                $defaultSkin = "charla";
            }

            $this->options = [
                "skin" => $defaultSkin,
                "theme" => "default",
                "sounds" => false,
            ];
            setcookie("SBOPTIONS", base64_encode(json_encode($this->options)), 2147483647);
        }

        // TODO: this should be moved to the Authentication class
        if (isset($_COOKIE["SBACCOUNTS"])) {
            $stupid_fucking_bullshit = str_replace($this->accounts_cookie_warning, "", $_COOKIE["SBACCOUNTS"]);
            $this->accounts = json_decode(base64_decode($stupid_fucking_bullshit), true);
        } else {
            $this->accounts = [];
        }

        try {
            $this->database = new Database($config["mysql"]);
            $this->authentication = new Authentication($this->database);
            $this->templating = new Templating($this->options, $this->database, $this->authentication);
        } catch (CoreException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database class.
     *
     * @return Database
     *
     */
    public function getDatabaseClass(): Database
    {
        return $this->database;
    }

    /**
     * Returns the authentication class.
     *
     * @return Authentication
     *
     */
    public function getAuthenticationClass(): Authentication
    {
        return $this->authentication;
    }

    /**
     * Returns the database class.
     *
     * @return Templating
     *
     */
    public function getTemplatingClass(): Templating
    {
        return $this->templating;
    }

    /**
     * Returns the user's local settings.
     *
     * TODO: this should be moved to a separate class
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
     * TODO: this should be moved to the Authentication class
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
     * TODO: this should be moved to the Authentication class
     *
     * @return array|string
     */
    public function getAccountsArray(): array|string
    {
        return $this->accounts;
    }
}