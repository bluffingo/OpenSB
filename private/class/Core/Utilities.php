<?php

namespace Core;

use JetBrains\PhpStorm\NoReturn;

class Utilities
{

    public static function rewritePHP(): void
    {
        if (str_contains($_SERVER["REQUEST_URI"], '.php'))
            self::redirectPerma('%s', str_replace('.php', '', $_SERVER["REQUEST_URI"]));
    }

    #[NoReturn] public static function redirectPerma($url, ...$args)
    {
        header('Location: ' . sprintf($url, ...$args), true, 301);
        die();
    }

    #[NoReturn] public static function redirect($url, ...$args)
    {
        header('Location: ' . sprintf($url, ...$args));
        die();
    }

    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") return Null;
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function isUsername($db, $string)
    {
        return($db->fetch("SELECT name FROM users where name = ?", [$string]));
    }
}