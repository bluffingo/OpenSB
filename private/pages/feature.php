<?php

namespace OpenSB;

global $database, $auth;

use SquareBracket\Utilities;

$id = ($_GET['v'] ?? null);

if (!$auth->isUserLoggedIn())
{
    Utilities::bannerNotification("Please login to continue.", "/login");
}

$submission = new \SquareBracket\UploadData($database, $id);

if (!$id) {
    Utilities::bannerNotification("You have not specified the upload.", "/");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    Utilities::bannerNotification("You cannot proceed with this action.", "/");
}

$data = $submission->getData();

if (!$data) {
    Utilities::bannerNotification("This upload does not exist.", "/");
}

if (!$auth->getUserID() == $data["author"]) {
    Utilities::bannerNotification("This is not your upload.", "/");
}

if ($database->query("UPDATE users SET featured_submission = ? WHERE id = ?",
    [$data["id"], $auth->getUserID()])) {
    Utilities::bannerNotification("You have successfully changed your profile's featured upload.", "/user?name=" . $auth->getUserData()["name"], "success");
}