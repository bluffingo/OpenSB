<?php

namespace OpenSB;

global $auth, $orange, $storage, $database;

use SquareBracket\UploadData;
use SquareBracket\UnorganizedFunctions;

$id = ($_GET['v'] ?? null);

$submission = new UploadData($orange->getDatabase(), $id);
$data = $submission->getData();

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("You might've logged out by accident. Re-enter your credentials.", "/login.php");
}

if ($auth->getUserID() != $data["author"]) {
    UnorganizedFunctions::Notification("This is not your submission.", "/");
}

$database->query("DELETE FROM videos WHERE video_id = ?", [$id]);
$database->query("INSERT INTO deleted_videos (id, uploaded_time, deleted_time) VALUES (?,?,?)", [$id, $data["time"], time()]);

$storage->deleteSubmission($data);

UnorganizedFunctions::Notification("Deleted.", "/my_uploads", "success");