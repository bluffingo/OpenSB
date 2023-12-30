<?php

namespace OpenSB;

global $orange, $auth;

use Orange\Utilities;

$id = ($_GET['v'] ?? null);
$db = $orange->getDatabase();

if (!$auth->isUserLoggedIn())
{
    Utilities::Notification("Please login to continue.", "/login.php");
}

$submission = new \Orange\SubmissionData($db, $id);

if (!$id) {
    Utilities::Notification("You have not specified the submission.", "/");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    Utilities::Notification("You cannot proceed with this action.", "/");
}

$data = $submission->getData();

if (!$data) {
    Utilities::Notification("This submission does not exist.", "/");
}

if (!$auth->getUserID() == $data["author"]) {
    Utilities::Notification("This is not your submission.", "/");
}

if ($db->query("UPDATE users SET featured_submission = ? WHERE id = ?",
    [$data["id"], $auth->getUserID()])) {
    Utilities::Notification("You have successfully changed your featured submission.", "/user?name=" . $auth->getUserData()["name"], "success");
}