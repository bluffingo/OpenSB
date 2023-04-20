<?php

namespace openSB;
/**
 * Returns true if it is executed from the command-line. (For command-line tools)
 */
function isCli()
{
    return php_sapi_name() == "cli";
}

function accessDenied()
{
    http_response_code(403);
    die(__("Access Denied"));
}

function redirect($url)
{
    header(sprintf('Location: %s', $url));
    die();
}

/**
 * Get hash of latest git commit
 *
 * @param bool $trim Trim the hash to the first 7 characters
 * @return void
 */
function gitCommit($trim = true)
{
    global $gitBranch;
    $commit = file_get_contents(__DIR__ . '/../../.git/refs/heads/' . $gitBranch); // kind of bad but hey it works

    if ($trim)
        return substr($commit, 0, 7);
    else
        return rtrim($commit);
}

/**
 * Get name of the platform that squareBracket is running on
 *
 * @return string Name of platform. It does NOT specify the Windows/macOS/Linux version.
 */
function getOS(): string
{
    return php_uname('s');
}

/**
 * Get the IP address of the user. Scandal incoming!
 *
 * @return mixed
 */
function getUserIpAddr()
{
    if (isCli()) return Null;
    $ip = $_SERVER['REMOTE_ADDR'];
    if (filter_var($ip, FILTER_VALIDATE_IP,
        FILTER_FLAG_IPV4 |
        FILTER_FLAG_IPV6 |
        FILTER_FLAG_NO_PRIV_RANGE |
        FILTER_FLAG_NO_RES_RANGE) === false) {
        die("Bullshit IP.");
    }
    return $ip;
}