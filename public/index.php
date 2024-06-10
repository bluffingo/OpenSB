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

// this is very ugly, i know.
function load_file_from_vendor($path, $content_type): void
{
    header("Content-Type: $content_type");
    require(SB_VENDOR_PATH . $path);
    die();
}

// Originally based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php

if (isset($path[1]) && $path[1] != '') {
    match ($path[1]) {
        '.well-known' => match ($path[2] ?? null) {
            // todo: nodeinfo so that opensb can be included within "fediverse" statistic pages
            'host-meta' => require(SB_PRIVATE_PATH . '/pages/activitypub/host-meta.php'),
            'webfinger' => require(SB_PRIVATE_PATH . '/pages/activitypub/webfinger.php'),
            default => die(),
        },
        'admin' => require(SB_PRIVATE_PATH . '/pages/admin.php'),
        'api' => match ($path[2] ?? null) {
            'finalium' => match ($path[3] ?? null) {
                'commenting.php' => require(SB_PRIVATE_PATH . '/pages/api/commenting.php'),
                'submission_interaction' => require(SB_PRIVATE_PATH . '/pages/api/submission_interaction.php'),
                'user_interaction.php' => require(SB_PRIVATE_PATH . '/pages/api/user_interaction.php'),
                default => die("Invalid API.")
            },
            default => die("Invalid API.")
        },
        'assets' => match ($path[2] ?? null) {
            'fa-solid-900.woff2' => load_file_from_vendor('/fortawesome/font-awesome/webfonts/fa-solid-900.woff2', 'font/woff2'),
            'bootstrap-icons.woff2' => load_file_from_vendor('/twbs/bootstrap-icons/font/fonts/bootstrap-icons.woff2', 'font/woff2'),
            default => die(),
        },
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
        default => UnorganizedFunctions::rewritePHP()
    };
} else {
    require(SB_PRIVATE_PATH . '/pages/index.php');
}