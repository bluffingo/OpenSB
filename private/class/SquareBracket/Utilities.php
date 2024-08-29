<?php

namespace SquareBracket;

class Utilities
{

    // if you use cloudflare and this function is returning
    // cloudflare ips. make sure you've properly configured your server.
    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") return null;

        return $_SERVER['REMOTE_ADDR'];
    }
}