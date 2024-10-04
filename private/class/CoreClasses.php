<?php
namespace OpenSB\class;

use OpenSB\class\Core\Authentication;
use OpenSB\class\Core\CoreException;
use OpenSB\class\Core\Database;
use OpenSB\class\Core\LocalOptions;
use OpenSB\class\Core\Templating;

/**
 * The core classes.
 */
class CoreClasses {
    private array $config;
    private LocalOptions $options;
    private Database $database;
    private Authentication $authentication;
    private Templating $templating;
    // TODO: these should be moved to the Authentication class
    private array $accounts;
    private string $accounts_cookie_warning = "DO-NOT-SHARE-THIS-WITH-ANYONE-";

    /**
     * Initialize core classes.
     */
    public function __construct($config) {
        $this->config = $config;

        // TODO: this should be moved to the Authentication class
        if (isset($_COOKIE["SBACCOUNTS"])) {
            $stupid_fucking_bullshit = str_replace($this->accounts_cookie_warning, "", $_COOKIE["SBACCOUNTS"]);
            $this->accounts = json_decode(base64_decode($stupid_fucking_bullshit), true);
        } else {
            $this->accounts = [];
        }

        try {
            $this->options = new LocalOptions();
            $this->database = new Database($config["mysql"]);
            $this->authentication = new Authentication($this->database);
            $this->templating = new Templating($this->options->getOptions(), $this->database, $this->authentication);
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
     * Returns the templating class.
     *
     * @return Templating
     *
     */
    public function getTemplatingClass(): Templating
    {
        return $this->templating;
    }

    /**
     * Returns the local options class.
     *
     * @return LocalOptions
     */
    public function getLocalOptionsClass(): LocalOptions
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

    public function isChazizSquareBracketInstance(): bool
    {
        return($this->config["site"] == "squarebracket_chaziz");
    }
}