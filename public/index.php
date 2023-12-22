<?php
// Based on Rollerozxa's router implementation in Principia-Web.
// https://github.com/principia-game/principia-web/blob/master/router.php
namespace Orange;

use Orange\MiscFunctions;

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

require_once dirname(__DIR__) . '/private/class/common.php';

var_dump($path);
echo("<br>");
var_dump($_GET);
echo("<br>");
var_dump($_POST);

function rewritePHP(): void
{
    if (str_contains($_SERVER["REQUEST_URI"], '.php'))
        MiscFunctions::redirectPerma('%s', str_replace('.php', '', $_SERVER["REQUEST_URI"]));
}

if (isset($path[1]) && $path[1] != '') {
    if ($path[1] == 'admin') {
        require(dirname(__DIR__) . '/private/pages/admin.php');
    } elseif ($path[1] == 'browse') {
        require(dirname(__DIR__) . '/private/pages/browse.php');
    } elseif ($path[1] == 'edit') {
        require(dirname(__DIR__) . '/private/pages/edit.php');
    } elseif ($path[1] == 'feature') {
        require(dirname(__DIR__) . '/private/pages/feature.php');
    } elseif ($path[1] == 'guidelines') {
        require(dirname(__DIR__) . '/private/pages/guidelines.php');
    } elseif ($path[1] == 'index') {
        require(dirname(__DIR__) . '/private/pages/index.php');
    } elseif ($path[1] == 'login') {
        require(dirname(__DIR__) . '/private/pages/login.php');
    } elseif ($path[1] == 'logout') {
        require(dirname(__DIR__) . '/private/pages/logout.php');
    } elseif ($path[1] == 'notices') {
        require(dirname(__DIR__) . '/private/pages/notices.php');
    } elseif ($path[1] == 'privacy') {
        require(dirname(__DIR__) . '/private/pages/privacy.php');
    } elseif ($path[1] == 'read') {
        require(dirname(__DIR__) . '/private/pages/read.php');
    } elseif ($path[1] == 'register') {
        require(dirname(__DIR__) . '/private/pages/register.php');
    } elseif ($path[1] == 'search') {
        require(dirname(__DIR__) . '/private/pages/search.php');
    } elseif ($path[1] == 'settings') {
        require(dirname(__DIR__) . '/private/pages/settings.php');
    } elseif ($path[1] == 'theme') {
        require(dirname(__DIR__) . '/private/pages/theme.php');
    } elseif ($path[1] == 'upload') {
        require(dirname(__DIR__) . '/private/pages/upload.php');
    } elseif ($path[1] == 'user') {
        require(dirname(__DIR__) . '/private/pages/user.php');
    } elseif ($path[1] == 'users') {
        require(dirname(__DIR__) . '/private/pages/users.php');
    } elseif ($path[1] == 'version') {
        require(dirname(__DIR__) . '/private/pages/version.php');
    } elseif ($path[1] == 'view') {
        require(dirname(__DIR__) . '/private/pages/watch.php');
    } elseif ($path[1] == 'watch') {
        MiscFunctions::redirect('/view/'.$_GET['v']);
    } elseif ($path[1] == 'write') {
        require(dirname(__DIR__) . '/private/pages/write.php');
    } else {
        rewritePHP();
    }
} else {
    require(dirname(__DIR__) . '/private/pages/index.php');
}