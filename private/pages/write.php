<?php

namespace OpenSB;

global $twig, $database, $disableWritingJournals, $auth, $isDebug;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

if ($auth->getUserBanData()) {
    UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
}

if ($disableWritingJournals) {
    UnorganizedFunctions::Notification("The ability to write journals has been disabled.", "/");
}

if ($database->result("SELECT COUNT(*) FROM journals WHERE date > ? AND author = ?", [time() - 180 , $auth->getUserID()]) && !$isDebug) {
    UnorganizedFunctions::Notification("Please wait three minutes before posting a journal again.", "/");
}

if (isset($_POST['upload']) or isset($_POST['upload_video']) and $auth->isUserLoggedIn()) {
    $uploader = $auth->getUserID();

    $title = ($post_data['title'] ?? null);
    $description = ($post_data['desc'] ?? null);

    $database->query("INSERT INTO journals (title, post, author, date) VALUES (?,?,?,?)",
        [$title, $description, $uploader, time()]);

    UnorganizedFunctions::Notification("Your journal has been posted.", "./user.php?name=" . $auth->getUserData()["name"], "success");
}

echo $twig->render('write.twig');
