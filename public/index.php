<?php
// NOTE: This code fucking sucks
namespace OpenSB;

global $enableFederatedStuff;

define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

use SquareBracket\UnorganizedFunctions;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

require_once SB_PRIVATE_PATH . '/class/common.php';

// hello to anyone reading this code. i know this fucking sucks. 2024 is supposed to be the last year of opensb.
// why am i not rewriting the codebase? because i already did that but it's for another project that's co-owned so
// unless if that project dies off i can't use that codebase for squarebracket. -chaziz 6/2/2024

// Based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php

if (isset($path[1]) && $path[1] != '') {
    match ($path[1]) {
        '.well-known' => match ($path[2] ?? null) {
            // todo: nodeinfo so that opensb can be included within "fediverse" statistic pages
            'host-meta' => require(SB_PRIVATE_PATH . '/pages/host-meta.php'),
            'webfinger' => require(SB_PRIVATE_PATH . '/pages/webfinger.php'),
            default => die(),
        },
        'admin' => require(SB_PRIVATE_PATH . '/pages/admin.php'),
        'browse' => require(SB_PRIVATE_PATH . '/pages/browse.php'),
        'delete' => require(SB_PRIVATE_PATH . '/pages/delete.php'),
        'edit' => require(SB_PRIVATE_PATH . '/pages/edit.php'),
        'feature' => require(SB_PRIVATE_PATH . '/pages/feature.php'),
        'guidelines' => require(SB_PRIVATE_PATH . '/pages/guidelines.php'),
        'index' => require(SB_PRIVATE_PATH . '/pages/index.php'),
        'license' => require(SB_PRIVATE_PATH . '/pages/license.php'),
        'login' => require(SB_PRIVATE_PATH . '/pages/login.php'),
        'logout' => require(SB_PRIVATE_PATH . '/pages/logout.php'),
        'my_submissions' => require(SB_PRIVATE_PATH . '/pages/my_submissions.php'),
        'notices' => require(SB_PRIVATE_PATH . '/pages/notices.php'),
        'privacy' => require(SB_PRIVATE_PATH . '/pages/privacy.php'),
        'read' => require(SB_PRIVATE_PATH . '/pages/read.php'),
        'register' => require(SB_PRIVATE_PATH . '/pages/register.php'),
        'search' => require(SB_PRIVATE_PATH . '/pages/search.php'),
        'settings' => require(SB_PRIVATE_PATH . '/pages/settings.php'),
        'theme' => require(SB_PRIVATE_PATH . '/pages/theme.php'),
        'upload' => require(SB_PRIVATE_PATH . '/pages/upload.php'),
        'user' => match ($_SERVER['HTTP_ACCEPT'] ?? null) {
            default => require(SB_PRIVATE_PATH . '/pages/user.php')
        },
        'users' => require(SB_PRIVATE_PATH . '/pages/users.php'),
        'version' => match ($path[2] ?? null) {
            'special_thanks' => require(SB_PRIVATE_PATH . '/pages/special_thanks.php'),
            default => require(SB_PRIVATE_PATH . '/pages/version.php'),
        },
        'view' => require(SB_PRIVATE_PATH . '/pages/watch.php'),
        'watch' => UnorganizedFunctions::redirect('/view/' . $_GET['v']),
        'write' => require(SB_PRIVATE_PATH . '/pages/write.php'),
        'api' => match ($path[2] ?? null) {
            'finalium' => match ($path[3] ?? null) {
                'commenting.php' => require(SB_PRIVATE_PATH . '/pages/api/commenting.php'),
                'submission_interaction' => require(SB_PRIVATE_PATH . '/pages/api/submission_interaction.php'),
                'user_interaction.php' => require(SB_PRIVATE_PATH . '/pages/api/user_interaction.php'),
                default => die("Invalid API.")
            },
            default => die("Invalid API.")
        },
        default => UnorganizedFunctions::rewritePHP()
    };
} else {
    require(SB_PRIVATE_PATH . '/pages/index.php');
}