<?php

namespace SquareBracket;

class Utilities
{
    public static function get_ip_address()
    {
        if (php_sapi_name() == "cli") {
            return null;
        }

        $cloudflare_v4 = [
            '173.245.48.0/20',
            '103.21.244.0/22',
            '103.22.200.0/22',
            '103.31.4.0/22',
            '141.101.64.0/18',
            '108.162.192.0/18',
            '190.93.240.0/20',
            '188.114.96.0/20',
            '197.234.240.0/22',
            '198.41.128.0/17',
            '162.158.0.0/15',
            '104.16.0.0/13',
            '104.24.0.0/14',
            '172.64.0.0/13',
            '131.0.72.0/22'
        ];
        $cloudflare_v6 = [
            '2400:cb00::/32',
            '2606:4700::/32',
            '2803:f800::/32',
            '2405:b500::/32',
            '2405:8100::/32'
        ];

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
        }

        return null;
    }

    private static function ip_in_range($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = 0xFFFFFFFF << (32 - (int)$mask);
            return ($ip & $mask) == ($subnet & $mask);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ip = inet_pton($ip);
            $subnet = inet_pton($subnet);
            $mask = str_repeat("f", (int)$mask / 4) . str_repeat("0", 32 - (int)$mask / 4);
            return (strcmp(substr($ip, 0, strlen($mask)), substr($subnet, 0, strlen($mask))) === 0);
        }
        return false;
    }
}