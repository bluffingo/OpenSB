<?php

namespace OpenSB;

global $twig, $database, $disableWritingJournals, $auth, $isDebug, $enableFederatedStuff;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

if ($auth->getUserBanData()) {
    UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
}

if ($disableWritingJournals) {
    if ($enableFederatedStuff) {
        UnorganizedFunctions::Notification("The ability to send messages has been disabled.", "/");
    } else {
        UnorganizedFunctions::Notification("The ability to write journals has been disabled.", "/");
    }
}

if (!$enableFederatedStuff) {
    if ($database->result("SELECT COUNT(*) FROM journals WHERE date > ? AND author = ?", [time() - 180, $auth->getUserID()]) && !$isDebug) {
        UnorganizedFunctions::Notification("Please wait three minutes before posting a journal again.", "/");
    }
}

if (isset($_POST['upload']) or isset($_POST['upload_video']) and $auth->isUserLoggedIn()) {
    // when the site is in fedi mode, the journals feature gets replaced with posts. for now, don't bother with outbox.
    // i'm still trying to figure out inbox. oh and we'll need to implement a rsa-256 hash signature thing? idfk but
    // still -chaziz 6/7/2024
    if ($enableFederatedStuff) {
        die("DOESN'T WORK RIGHT NOW! INCOMPLETE!!!");
    } else {
    $uploader = $auth->getUserID();

    $title = ($_POST['title'] ?? "No title");
    $description = ($_POST['desc'] ?? null);

    $database->query("INSERT INTO journals (title, post, author, date) VALUES (?,?,?,?)",
        [$title, $description, $uploader, time()]);

    UnorganizedFunctions::Notification("Your journal has been posted.", "./user.php?name=" . $auth->getUserData()["name"], "success");
    }
}

if ($enableFederatedStuff) {
    echo $twig->render('write_post.twig');
} else {
    echo $twig->render('write_journal.twig');
}