<?php

namespace OpenSB;

global $database, $twig, $enableChat, $auth;

use SquareBracket\UnorganizedFunctions;

if (!$enableChat)
{
    UnorganizedFunctions::Notification("Chatting is disabled on this instance.", "/");
}

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

if ($auth->getUserBanData())
{
    UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
}

echo $twig->render('chat.twig');
