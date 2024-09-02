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
    UnorganizedFunctions::bannerNotification("Please login to continue.", "/login.php");
}

if ($auth->getUserID() != $data["author"]) {
    UnorganizedFunctions::bannerNotification("This is not your upload.", "/");
}

$database->query("DELETE FROM videos WHERE video_id = ?", [$id]);
$database->query("INSERT INTO deleted_videos (id, uploaded_time, deleted_time) VALUES (?,?,?)", [$id, $data["time"], time()]);

$storage->deleteSubmission($data);

UnorganizedFunctions::bannerNotification("This upload has been successfully deleted.", "/my_uploads", "success");