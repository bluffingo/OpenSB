<?php

namespace Orange;

global $host, $user, $pass, $db, $isQoboTV, $isMaintenance;

use GUMP;

foreach (glob(dirname(__DIR__) . "/orange/interfaces/*.php") as $file) {
    require_once($file);
}

foreach (glob(dirname(__DIR__) . "/orange/classes/*.php") as $file) {
    require_once($file);
}

/**
 * @since 0.1.0
 */
class Orange {
    private \Orange\Database $database;
    public string $version = "Orange 1.0";
    public array $options;

    public function __construct($host, $user, $pass, $db) {
        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        } else {
            $this->options = [];
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
     * Returns the database for other Betty classes to use.
     *
     * @since 0.1.0
     *
     * @return Database
     */
    public function getBettyDatabase(): \Orange\Database {
        return $this->database;
    }

    /**
     * Returns Betty's version number.
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getBettyVersion(): string
    {
        return $this->version;
    }

    /**
     * Returns the user's local settings.
     *
     * @since 0.1.0
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
     * @since 0.1.0
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

    public function randomString($length)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-"),0,$length);
    }
}


$betty = new \Orange\Orange($host, $user, $pass, $db);
$auth = new \Orange\Authentication($betty->getBettyDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new \Orange\Profiler();
$gump = new GUMP('en');

if ($isMaintenance) {
    $twig = new \Orange\Templating($betty);
    echo $twig->render("error.twig", [
        "error_title" => "Offline",
        "error_reason" => "This site is currently offline."
    ]);
    die();
}

if ( $ipban = $betty->getBettyDatabase()->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [MiscFunctions::get_ip_address()])) {
    $twig = new \Orange\Templating($betty);
    echo $twig->render("error.twig", [
        "error_title" => "IP Banned",
        "error_reason" => "You are IP banned from the site.",
        // legacy opensb would show the reason, but i think these ip ban reasons should be kept internal.
    ]);
    die();
}

if ($isQoboTV) {
    $storage = new \Orange\BunnyStorage($betty);
} else {
    $storage = new \Orange\LocalStorage($betty);
}