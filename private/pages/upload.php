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
    UnorganizedFunctions::Notification("The ability to upload has been disabled.", "/");
}

if (!$auth->isUserAdmin()) {
    // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
    if ($database->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 120, $auth->getUserID()]) && !$isDebug) {
        UnorganizedFunctions::Notification("Please wait two minutes before uploading again.", "/");
    }
}

function parse_tags($tags, $submission_id, $database) {
    // parse tags from input
    $tagsID = [];
    foreach ($tags as $tag) {
        $tagId = $database->result("SELECT tag_id FROM tag_meta WHERE name = ?", [$tag]);

        if ($tagId === false) {
            $database->query("INSERT INTO tag_meta (name, latestUse) VALUES (?,?)", [$tag, time()]);
            $tagId = $database->insertId(); // Get the ID of the newly inserted tag
        } else {
            $database->query("UPDATE tag_meta SET latestUse = ? WHERE name = ?", [time(), $tag]);
        }

        $tagsID[] = $tagId;
    }

    $submission_integer_id = $database->result("SELECT id from videos WHERE video_id = ?", [$submission_id]);

    // link tags to the submission
    foreach ($tagsID as $tagID) {
        if (!$database->result("SELECT tag_id FROM tag_index WHERE tag_id = ? AND video_id = ?", [$tagID, $submission_integer_id])) {
            $database->query("INSERT INTO tag_index (video_id, tag_id) VALUES (?,?)", [$submission_integer_id, $tagID]);
        }
    }
}

if (isset($_POST['upload']) or isset($_POST['upload_video']) and $auth->isUserLoggedIn()) {
    $new = UnorganizedFunctions::generateRandomizedString(11, true);
    $uploader = $auth->getUserID();

    $title = ($_POST['title'] ?? null);
    $description = ($_POST['desc'] ?? null);
    $rating = isset($_POST['rating']) && $_POST['rating'] === 'true' ? 'mature' : 'general';
    $tags = ($_POST['tags'] ?? '');
    if ($tags === '') {
        $tags2 = [];
    } else {
        $tags2 = preg_split('/[\s,]+/', trim($tags, ","));
    }

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
                [$new, $title, $description, $uploader, time(), json_encode($tags2), 'dynamic/videos/' . $new, $status, $rating]);

            if (!isset($noProcess)) {
                $storage->processVideo($new, $target_file);
            }

            parse_tags($tags2, $new, $database);

            UnorganizedFunctions::Notification("Your upload has been completed.", "./watch.php?v=" . $new, "success");
        } else {
            UnorganizedFunctions::Notification("There is a problem with file permissions and/or PHP on this instance.", "/upload");
        }
    } elseif (in_array(strtolower($ext), $supportedImageFormats, true)) {
        $storage->processImage($temp_name, $new);
        $status = 0x0;
        $database->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type, rating) VALUES (?,?,?,?,?,?,?,?,?,?)",
            [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), '/dynamic/art/' . $new . '.png', $status, 2, $rating]);

        parse_tags($tags2, $new, $database);

        UnorganizedFunctions::Notification("Your upload has been completed.", "./watch.php?v=" . $new, "success");
    } else {
        UnorganizedFunctions::Notification("This file format is not supported.", "/upload");
    }
}

echo $twig->render('upload.twig', [
    'limit' => (UnorganizedFunctions::convertBytes(ini_get('upload_max_filesize'))),
]);;
