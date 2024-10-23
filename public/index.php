<?php
namespace OpenSB;

define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

use JetBrains\PhpStorm\NoReturn;
use SquareBracket\Utilities;

require_once SB_PRIVATE_PATH . '/class/common.php';

global $auth;

#[NoReturn] function load_thumbnail_from_skin($path) {
    $pathParts = explode('_', $path);
    $skin = $pathParts[0] ?? '';
    $theme = $pathParts[1] ?? 'default.png';

    $skinPath = SB_PRIVATE_PATH . '/skins/' . $skin . '/' . $theme;

    if (file_exists($skinPath)) {
        header('Content-Type: image/png');
        readfile($skinPath);
        exit;
    } else {
        Utilities::redirect('/assets/unknown_theme.png');
    }
}


// this is very ugly, i know.
#[NoReturn] function load_file_from_vendor($path, $content_type): void
{
    header("Content-Type: $content_type");
    readfile(SB_VENDOR_PATH . $path);
    exit;
}

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

// Originally based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php

if (isset($path[1]) && $path[1] != '') {
    match ($path[1]) {
        'admin' => match ($path[2] ?? null) {
            'login' => require(SB_PRIVATE_PATH . '/pages/admin_login.php'),
            'users' => match ($path[3] ?? null) {
                    $path[3] ?? null => (!empty($path[3]) && $path[3] !== '')
                    ? require(SB_PRIVATE_PATH . '/pages/admin_user_edit.php')
                    : require(SB_PRIVATE_PATH . '/pages/admin_users.php'),
                default => require(SB_PRIVATE_PATH . '/pages/admin_users.php'),
            },
            'overview' => require(SB_PRIVATE_PATH . '/pages/admin_overview.php'),
            'uploads' => require(SB_PRIVATE_PATH . '/pages/admin_uploads.php'),
            'interactions' => require(SB_PRIVATE_PATH . '/pages/admin_interactions.php'),
            'invitekeys' => require(SB_PRIVATE_PATH . '/pages/admin_invitekeys.php'),
            default => Utilities::redirect('/admin/overview/'),
        },
        'api' => match ($path[2] ?? null) {
            'biscuit' => match ($path[3] ?? null) {
                'commenting' => require(SB_PRIVATE_PATH . '/pages/api/biscuit/commenting.php'),
                'submission_interaction' => require(SB_PRIVATE_PATH . '/pages/api/biscuit/submission_interaction.php'),
                'user_interaction' => require(SB_PRIVATE_PATH . '/pages/api/biscuit/user_interaction.php'),
                default => die("Invalid API.")
            },
            'legacy' => match ($path[3] ?? null) {
                'comment' => require(SB_PRIVATE_PATH . '/pages/api/legacy/comment.php'),
                'rate' => require(SB_PRIVATE_PATH . '/pages/api/legacy/rate.php'),
                'subscribe' => require(SB_PRIVATE_PATH . '/pages/api/legacy/subscribe.php'),
                default => die("Invalid API.")
            },
            'v2' => match ($path[3] ?? null) { //TODO
                //'get_comments' => require(SB_PRIVATE_PATH . '/pages/api/v2/get_comments.php'),
                //'get_statistics' => require(SB_PRIVATE_PATH . '/pages/api/v2/get_statistics.php'),
                //'get_upload' => require(SB_PRIVATE_PATH . '/pages/api/v2/get_upload.php'),
                //'get_uploads' => require(SB_PRIVATE_PATH . '/pages/api/v2/get_uploads.php'),
                default => die(json_encode("Invalid API."))
            },
            default => die("Invalid API.")
        },
        'assets' => match ($path[2] ?? null) {
            'bootstrap-icons.woff2' => load_file_from_vendor('/twbs/bootstrap-icons/font/fonts/bootstrap-icons.woff2', 'font/woff2'),
            'previews' => load_thumbnail_from_skin($path[3] ?? ''),
            default => die(),
        },
        'browse' => require(SB_PRIVATE_PATH . '/pages/browse.php'),
        'delete' => require(SB_PRIVATE_PATH . '/pages/delete.php'),
        'design_test' => require(SB_PRIVATE_PATH . '/pages/design_test.php'),
        'dev' => match ($path[2] ?? null) {
            'upload' => require(SB_PRIVATE_PATH . '/pages/dev/upload.php'),
            default => die(),
        },
        'edit' => require(SB_PRIVATE_PATH . '/pages/edit.php'),
        'feature' => require(SB_PRIVATE_PATH . '/pages/feature.php'),
        'guidelines' => require(SB_PRIVATE_PATH . '/pages/guidelines.php'),
        'index' => require(SB_PRIVATE_PATH . '/pages/index.php'),
        'license' => require(SB_PRIVATE_PATH . '/pages/license.php'),
        'login' => require(SB_PRIVATE_PATH . '/pages/login.php'),
        'logout' => require(SB_PRIVATE_PATH . '/pages/logout.php'),
        'my_submissions' => Utilities::redirect('/my_uploads'),
        'my_uploads' => require(SB_PRIVATE_PATH . '/pages/my_uploads.php'),
        'notices' => require(SB_PRIVATE_PATH . '/pages/notices.php'),
        'privacy' => require(SB_PRIVATE_PATH . '/pages/privacy.php'),
        'read' => require(SB_PRIVATE_PATH . '/pages/read.php'),
        'register' => require(SB_PRIVATE_PATH . '/pages/register.php'),
        'search' => require(SB_PRIVATE_PATH . '/pages/search.php'),
        'settings' => require(SB_PRIVATE_PATH . '/pages/settings.php'),
        'staff' => require(SB_PRIVATE_PATH . '/pages/staff.php'),
        'theme' => require(SB_PRIVATE_PATH . '/pages/theme.php'),
        'tos' => require(SB_PRIVATE_PATH . '/pages/tos.php'),
        'upload' => require(SB_PRIVATE_PATH . '/pages/upload.php'),
        'user' => match ($_SERVER['HTTP_ACCEPT'] ?? null) {
            default => require(SB_PRIVATE_PATH . '/pages/user.php')
        },
        'users' => require(SB_PRIVATE_PATH . '/pages/users.php'),
        'verify_birthdate' => require(SB_PRIVATE_PATH . '/pages/verify_birthdate.php'),
        'version' => require(SB_PRIVATE_PATH . '/pages/version.php'),
        'view' => require(SB_PRIVATE_PATH . '/pages/watch.php'),
        'watch' => Utilities::redirect('/view/' . $_GET['v']),
        'write' => require(SB_PRIVATE_PATH . '/pages/write.php'),
        default => Utilities::rewritePHP()
    };
} else {
    require(SB_PRIVATE_PATH . '/pages/index.php');
}