<?php

namespace openSB;

global $betty, $auth;
require_once dirname(__DIR__) . '/private/class/common.php';

$id = ($_GET['v'] ?? null);
$db = $betty->getBettyDatabase();

if (!$auth->isUserLoggedIn())
{
    $betty->Notification("Please login to continue.", "/login.php");
}

$submission = new \Orange\SubmissionData($db, $id);

if (!$id) {
    $betty->Notification("You have not specified the submission.", "/");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    $betty->Notification("You cannot proceed with this action.", "/");
}

$data = $submission->getData();

if (!$data) {
    $betty->Notification("This submission does not exist.", "/");
}

if (!$auth->getUserID() == $data["author"]) {
    $betty->Notification("This is not your submission.", "/");
}

if ($db->query("UPDATE users SET featured_submission = ? WHERE id = ?",
    [$data["id"], $auth->getUserID()])) {
    $betty->Notification("You have successfully changed your featured submission.", "/user?name=" . $auth->getUserData()["name"], "success");
}