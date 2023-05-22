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
 * Get IP.
 *
 * @return mixed
 */
function getUserIpAddr()
{
    if (isCli()) return Null;
    $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}