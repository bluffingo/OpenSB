<?php

namespace Orange;

global $orange, $auth;
require_once dirname(__DIR__) . '/class/common.php';

$id = ($_GET['v'] ?? null);
$db = $orange->getDatabase();

if (!$auth->isUserLoggedIn())
{
    $orange->Notification("Please login to continue.", "/login.php");
}

$submission = new \Orange\SubmissionData($db, $id);

if (!$id) {
    $orange->Notification("You have not specified the submission.", "/");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    $orange->Notification("You cannot proceed with this action.", "/");
}

$data = $submission->getData();

if (!$data) {
    $orange->Notification("This submission does not exist.", "/");
}

if (!$auth->getUserID() == $data["author"]) {
    $orange->Notification("This is not your submission.", "/");
}

if ($db->query("UPDATE users SET featured_submission = ? WHERE id = ?",
    [$data["id"], $auth->getUserID()])) {
    $orange->Notification("You have successfully changed your featured submission.", "/user?name=" . $auth->getUserData()["name"], "success");
}