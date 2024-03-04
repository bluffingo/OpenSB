<?php
// Based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php
namespace OpenSB;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN Orange CLASS.

// SB_PUBLIC_PATH is not needed because all the core functionality is in the private folder.

use Orange\Utilities;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

require_once SB_PRIVATE_PATH . '/class/common.php';
function rewritePHP(): void
{
    if (str_contains($_SERVER["REQUEST_URI"], '.php'))
        Utilities::redirectPerma('%s', str_replace('.php', '', $_SERVER["REQUEST_URI"]));
}

if (isset($path[1]) && $path[1] != '') {
    if ($path[1] == 'admin') {
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
        require(SB_PRIVATE_PATH . '/pages/user.php');
    } elseif ($path[1] == 'users') {
        require(SB_PRIVATE_PATH . '/pages/users.php');
    } elseif ($path[1] == 'version') {
        require(SB_PRIVATE_PATH . '/pages/version.php');
    } elseif ($path[1] == 'view') {
        require(SB_PRIVATE_PATH . '/pages/watch.php');
    } elseif ($path[1] == 'watch') {
        Utilities::redirect('/view/'.$_GET['v']);
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
        } elseif ($path[2] == 'bluffingo_updater_test') {
            if (!isset($path[3])) {
                die("Invalid API.");
            } elseif ($path[3] == 'get_versions') {
                if (isset($path[4])) {
                    require(SB_PRIVATE_PATH . '/pages/api/blupd_test.php');
                } else {
                    die("Missing date.");
                }
            } elseif ($path[3] == 'get_software') {
                require(SB_PRIVATE_PATH . '/pages/api/blupd_test_2.php');
            }
        } else {
            die("Invalid API.");
        }
    } else {
        rewritePHP();
    }
} else {
    require(SB_PRIVATE_PATH . '/pages/index.php');
}