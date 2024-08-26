<?php

namespace SquareBracket;

class Utilities
{
    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") {
            return null;
        }

        $cloudflare_v4 = ['199.27.128.0/21', '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
            '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22',
            '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/12'];
        $cloudflare_v6 = ['2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32'];

        $client_ip = null;

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $client_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $client_ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($client_ip) {
            if (filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                foreach ($cloudflare_v4 as $cidr) {
                    if (self::ip_in_range($client_ip, $cidr)) {
                        return $client_ip;
                    }
                }
            } elseif (filter_var($client_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                foreach ($cloudflare_v6 as $cidr) {
                    if (self::ip_in_range($client_ip, $cidr)) {
                        return $client_ip;
                    }
                }
            }

            // if the cloudflare ip is invalid, fall back to remote address.
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    private static function ip_in_range($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = 0xFFFFFFFF << (32 - $mask);
            return ($ip & $mask) == ($subnet & $mask);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $subnet = inet_pton($subnet);
            $ip = inet_pton($ip);
            $mask = str_repeat("f", $mask / 4) . str_repeat("0", 32 - $mask / 4);
            return ($ip & $mask) == ($subnet & $mask);
        }
        return false;
    }
}