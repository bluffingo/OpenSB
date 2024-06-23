<?php

namespace OpenSB;

global $twig, $database, $auth, $storage;

use SquareBracket\UploadData;
use SquareBracket\UnorganizedFunctions;

if (isset($_POST['upload'])) {
    $id = ($_POST['vid_id'] ?? null);
} else {
    $id = ($_GET['v'] ?? null);
}

$submission = new UploadData($database, $id);
$data = $submission->getData();

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

if ($auth->getUserBanData() || $submission->getTakedown()) {
    UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
}

if ($auth->getUserID() != $data["author"]) {
    UnorganizedFunctions::Notification("This is not your submission.", "/");
}

if (isset($_POST['upload'])) {
    $title = $data['title'] ?? null;
    $desc = $data['desc'] ?? null;

    if (!empty($_FILES['thumbnail']['name'])) {
        $name = $_FILES['thumbnail']['name'];
        $temp_name = $_FILES['thumbnail']['tmp_name'];
        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $target_file = SB_DYNAMIC_PATH . '/custom_thumbnails/' . $data["video_id"] . '.jpg';
        $storage->uploadCustomThumbnail($temp_name, $target_file);
    }

    $database->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
        [$title, $desc, $id]);
    UnorganizedFunctions::Notification("Your submission's details have been modified.", "/view/" . $id, "success");
}

$infoData = [
    "int_id" => $data["id"],
    "id" => $data["video_id"],
    "title" => $data["title"],
    "description" => $data["description"],
    "published" => $data["time"],
    "type" => $data["post_type"],
];

echo $twig->render('edit.twig', [
    'data' => $infoData,
]);