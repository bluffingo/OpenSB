<?php

namespace OpenSB;

global $database, $auth;

use SquareBracket\UnorganizedFunctions;

$id = ($_GET['v'] ?? null);

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::bannerNotification("Please login to continue.", "/login.php");
}

$submission = new \SquareBracket\UploadData($database, $id);

if (!$id) {
    UnorganizedFunctions::bannerNotification("You have not specified the upload.", "/");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    UnorganizedFunctions::bannerNotification("You cannot proceed with this action.", "/");
}

$data = $submission->getData();

if (!$data) {
    UnorganizedFunctions::bannerNotification("This upload does not exist.", "/");
}

if (!$auth->getUserID() == $data["author"]) {
    UnorganizedFunctions::bannerNotification("This is not your upload.", "/");
}

if ($database->query("UPDATE users SET featured_submission = ? WHERE id = ?",
    [$data["id"], $auth->getUserID()])) {
    UnorganizedFunctions::bannerNotification("You have successfully changed your profile's featured upload.", "/user?name=" . $auth->getUserData()["name"], "success");
}