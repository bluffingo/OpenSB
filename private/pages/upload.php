<?php

namespace OpenSB;

global $database, $twig, $disableUploading, $auth, $isDebug, $storage;

use SquareBracket\Pages\SubmissionUpload;
use SquareBracket\Templating;
use SquareBracket\UnorganizedFunctions;

$supportedVideoFormats = ["mp4", "mkv", "wmv", "flv", "avi", "mov", "3gp"];
$supportedImageFormats = ["png", "jpg", "jpeg"];

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

if ($auth->getUserBanData()) {
    UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
}

if ($disableUploading) {
    UnorganizedFunctions::Notification("The ability to upload submissions has been disabled.", "/");
}

if (!$auth->isUserAdmin()) {
    // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
    if ($database->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 120, $auth->getUserID()]) && !$isDebug) {
        UnorganizedFunctions::Notification("Please wait two minutes before uploading again.", "/");
    }
}

if (isset($_POST['upload']) or isset($_POST['upload_video']) and $auth->isUserLoggedIn()) {
    $new = UnorganizedFunctions::generateRandomizedString(11, true);
    $uploader = $auth->getUserID();

    $title = ($_POST['title'] ?? null);
    $description = ($_POST['desc'] ?? null);
    $rating = isset($_POST['rating']) && $_POST['rating'] === 'true' ? 'mature' : 'general';
    if ($isDebug) {
        $noProcess = ($_POST['debugUploaderSkip'] ?? null);
    }

    $name = $_FILES['fileToUpload']['name'];
    $temp_name = $_FILES['fileToUpload']['tmp_name']; // gets upload info
    $ext = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), $supportedVideoFormats, true)) {
        if (isset($noProcess) && $isDebug) {
            $status = 0x0; // pretend that video has been successfully uploaded
            $target_file = SB_DYNAMIC_PATH . '/dynamic/videos/' . $new . '.converted.' . $ext;
        } else {
            $status = 0x2;
            $target_file = SB_DYNAMIC_PATH . '/videos/' . $new . '.' . $ext;
        }
        if (move_uploaded_file($temp_name, $target_file)) {
            $database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, rating) VALUES (?,?,?,?,?,?,?,?,?)",
                [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), 'dynamic/videos/' . $new, $status, $rating]);

            if (!isset($noProcess)) {
                $storage->processVideo($new, $target_file);
            }

            UnorganizedFunctions::Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
        } else {
            UnorganizedFunctions::Notification("There's a problem with permissions and/or PHP File configuration on the server. Please contact the instance staff.", "/upload");
        }
    } elseif (in_array(strtolower($ext), $supportedImageFormats, true)) {
        $storage->processImage($temp_name, $new);
        $status = 0x0;
        $database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type, rating) VALUES (?,?,?,?,?,?,?,?,?,?)",
            [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), '/dynamic/art/' . $new . '.png', $status, 2, $rating]);

        UnorganizedFunctions::Notification("Your submission has been uploaded.", "./watch.php?v=" . $new, "success");
    } else {
        UnorganizedFunctions::Notification("This file format is not supported.", "/upload");
    }
}

echo $twig->render('upload.twig');
