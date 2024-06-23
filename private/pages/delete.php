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

// INSERT INTO `deleted_videos` (`autoint`, `id`, `uploaded_time`, `deleted_time`) VALUES ('1', 'sex', '231', '312');

$database->query("DELETE FROM videos WHERE video_id = ?", [$id]);
// NOTE FOR BITQOBO DEVS: for squarebracket-to-bitqobo migration, moved_to_bitqobo should be set to 1. this will
// redirect users from squarebracket.pw to qobo.tv if they stumble upon a video that's been migrated. -chaziz 5/14/2024
$database->query("INSERT INTO deleted_videos (id, uploaded_time, deleted_time, moved_to_bitqobo) VALUES (?,?,?,?)", [$id, $data["time"], time(), 0]);

$storage->deleteSubmission($data);

UnorganizedFunctions::Notification("Deleted.", "/my_submissions", "success");