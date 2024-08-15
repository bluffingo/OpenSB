<?php

namespace OpenSB;

global $isChazizSB, $twig, $enableChat, $auth;

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

if ($isChazizSB) {
    // hardcode to chat.bluffingo.net since getting jack to update squarebracket.pw cloudflare shit
    // would take weeks. (lol)
    $url = "wss://chat.bluffingo.net/";
} else {
    $host = $_SERVER['HTTP_HOST'];
    $url ="ws://$host:47101/";
}

echo $twig->render('chat.twig', ["url" => $url]);
