<?php

namespace SquareBracket;

class Utilities
{
    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") return Null;
        return $_SERVER['REMOTE_ADDR'];
    }
}