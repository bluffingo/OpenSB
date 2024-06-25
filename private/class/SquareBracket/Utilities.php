<?php

namespace SquareBracket;

class Utilities
{
    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") return null;

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        //} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //    return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }
}