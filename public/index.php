<?php
// Based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php
namespace OpenSB;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

// SB_PUBLIC_PATH is not needed because all the core functionality is in the private folder.

use Core\Utilities as UtilitiesAlias;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

require_once SB_PRIVATE_PATH . '/class/common.php';

// i don't think this is how it should be done, but whatever. if you're a developer looking to implement
// stuff like activitypub or webfinger or whatever, i don't recommend using opensb as reference, i am a
// self-taught programmer and as such my code quality is abysmal. -bluffingo 3/30/2024

if (isset($path[1]) && $path[1] != '') {
    if ($path[1] == '.well-known') {
        // todo: nodeinfo so that opensb can be included within "fediverse" statistic pages
        if ($path[2] == 'webfinger') { // let's start with implementing webfinger.
            require(SB_PRIVATE_PATH . '/pages/webfinger.php');
        }
    } elseif ($path[1] == 'admin') {
        require(SB_PRIVATE_PATH . '/pages/admin.php');
    } elseif ($path[1] == 'browse') {
        require(SB_PRIVATE_PATH . '/pages/browse.php');
    } elseif ($path[1] == 'edit') {
        require(SB_PRIVATE_PATH . '/pages/edit.php');
    } elseif ($path[1] == 'feature') {
        require(SB_PRIVATE_PATH . '/pages/feature.php');
    } elseif ($path[1] == 'guidelines') {
        require(SB_PRIVATE_PATH . '/pages/guidelines.php');
    } elseif ($path[1] == 'index') {
        require(SB_PRIVATE_PATH . '/pages/index.php');
    } elseif ($path[1] == 'license') {
        require(SB_PRIVATE_PATH . '/pages/license.php');
    } elseif ($path[1] == 'login') {
        require(SB_PRIVATE_PATH . '/pages/login.php');
    } elseif ($path[1] == 'logout') {
        require(SB_PRIVATE_PATH . '/pages/logout.php');
    } elseif ($path[1] == 'notices') {
        require(SB_PRIVATE_PATH . '/pages/notices.php');
    } elseif ($path[1] == 'privacy') {
        require(SB_PRIVATE_PATH . '/pages/privacy.php');
    } elseif ($path[1] == 'read') {
        require(SB_PRIVATE_PATH . '/pages/read.php');
    } elseif ($path[1] == 'register') {
        require(SB_PRIVATE_PATH . '/pages/register.php');
    } elseif ($path[1] == 'search') {
        require(SB_PRIVATE_PATH . '/pages/search.php');
    } elseif ($path[1] == 'settings') {
        require(SB_PRIVATE_PATH . '/pages/settings.php');
    } elseif ($path[1] == 'theme') {
        require(SB_PRIVATE_PATH . '/pages/theme.php');
    } elseif ($path[1] == 'upload') {
        require(SB_PRIVATE_PATH . '/pages/upload.php');
    } elseif ($path[1] == 'user') {
        if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], "application/ld+json")) {
            require(SB_PRIVATE_PATH . '/pages/user_json.php');
        } else {
            require(SB_PRIVATE_PATH . '/pages/user.php');
        }
    } elseif ($path[1] == 'users') {
        require(SB_PRIVATE_PATH . '/pages/users.php');
    } elseif ($path[1] == 'version') {
        require(SB_PRIVATE_PATH . '/pages/version.php');
    } elseif ($path[1] == 'view') {
        require(SB_PRIVATE_PATH . '/pages/watch.php');
    } elseif ($path[1] == 'watch') {
        UtilitiesAlias::redirect('/view/' . $_GET['v']);
    } elseif ($path[1] == 'write') {
        require(SB_PRIVATE_PATH . '/pages/write.php');
    } elseif ($path[1] == 'api') {
        if ($path[2] == 'finalium') {
            if (!isset($path[3])) {
                die("Invalid API.");
            } elseif ($path[3] == 'commenting.php') {
                require(SB_PRIVATE_PATH . '/pages/api/commenting.php');
            } elseif ($path[3] == 'submission_interaction') {
                require(SB_PRIVATE_PATH . '/pages/api/submission_interaction.php');
            } elseif ($path[3] == 'user_interaction.php') {
                require(SB_PRIVATE_PATH . '/pages/api/user_interaction.php');
            }
        } else {
            die("Invalid API.");
        }
    } else {
        UtilitiesAlias::rewritePHP();
    }
} else {
    require(SB_PRIVATE_PATH . '/pages/index.php');
}