<?php
// Based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php
namespace OpenSB;

global $enableFederatedStuff;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PUBLIC_PATH", dirname(__DIR__) . '/public'); // in reality, we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

use SquareBracket\UnorganizedFunctions;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

require_once SB_PRIVATE_PATH . '/class/common.php';

// if you're a developer looking to implement stuff like activitypub or webfinger or whatever,
// i don't recommend using opensb as reference, i am a self-taught programmer and as such my
// code quality is abysmal. -bluffingo 3/30/2024

if (isset($path[1]) && $path[1] != '') {
    match ($path[1]) {
        '.well-known' => match ($path[2] ?? null) {
            // todo: nodeinfo so that opensb can be included within "fediverse" statistic pages
            'webfinger' => require(SB_PRIVATE_PATH . '/pages/webfinger.php'),
            default => die(),
        },
        'admin' => require(SB_PRIVATE_PATH . '/pages/admin.php'),
        'browse' => require(SB_PRIVATE_PATH . '/pages/browse.php'),
        'edit' => require(SB_PRIVATE_PATH . '/pages/edit.php'),
        'feature' => require(SB_PRIVATE_PATH . '/pages/feature.php'),
        'guidelines' => require(SB_PRIVATE_PATH . '/pages/guidelines.php'),
        'index' => require(SB_PRIVATE_PATH . '/pages/index.php'),
        'license' => require(SB_PRIVATE_PATH . '/pages/license.php'),
        'login' => require(SB_PRIVATE_PATH . '/pages/login.php'),
        'logout' => require(SB_PRIVATE_PATH . '/pages/logout.php'),
        'notices' => require(SB_PRIVATE_PATH . '/pages/notices.php'),
        'privacy' => require(SB_PRIVATE_PATH . '/pages/privacy.php'),
        'read' => require(SB_PRIVATE_PATH . '/pages/read.php'),
        'register' => require(SB_PRIVATE_PATH . '/pages/register.php'),
        'search' => require(SB_PRIVATE_PATH . '/pages/search.php'),
        'settings' => require(SB_PRIVATE_PATH . '/pages/settings.php'),
        'theme' => require(SB_PRIVATE_PATH . '/pages/theme.php'),
        'upload' => require(SB_PRIVATE_PATH . '/pages/upload.php'),
        'user' => match ($_SERVER['HTTP_ACCEPT'] ?? null) {
            ($enableFederatedStuff && str_contains($_SERVER['HTTP_ACCEPT'], "application/ld+json")) => require(SB_PRIVATE_PATH . '/pages/user_json.php'),
            default => require(SB_PRIVATE_PATH . '/pages/user.php')
        },
        'users' => require(SB_PRIVATE_PATH . '/pages/users.php'),
        'version' => require(SB_PRIVATE_PATH . '/pages/version.php'),
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