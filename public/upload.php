<?php

namespace openSB;

//this uploads and converts the video, should switch to a better solution!
global $betty;
require_once dirname(__DIR__) . '/private/class/common.php';

$supportedVideoFormats = ["mp4", "mkv", "wmv", "flv", "avi", "mov", "3gp"];
$supportedImageFormats = ["png", "jpg", "jpeg"];

//die(dirname(__DIR__) . '/private/scripts/processingworker.php');

if ($userbandata) {
    error(403, __("You are currently banned and cannot proceed with this action."));
}

if ($disableUploading) {
    $betty->Notification("Uploading is disabled.", "/");
}


use \Intervention\Image\ImageManager;

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $uploader = $userdata['id'];
    $new = randstr(11);

    $title = ($_POST['title'] ?? null);
    $description = ($_POST['desc'] ?? null);
    if ($isDebug) {
        $noProcess = ($_POST['debugUploaderSkip'] ?? null);
    }

    // Rate limit uploading to a minute, both to prevent spam and to prevent double uploads.
    if ($sql->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 180 , $userdata['id']]) && !$isDebug) {
        die(__("Please wait three minutes before uploading again."));
    }

    $name = $_FILES['fileToUpload']['name'];
    $temp_name = $_FILES['fileToUpload']['tmp_name']; // gets upload info
    $ext = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), $supportedVideoFormats, true)) {
        if (isset($noProcess) && $isDebug) {
            $status = 0x0; // pretend that video has been successfully uploaded
            $target_file = dirname(__DIR__) . '/dynamic/videos/' . $new . '.converted.' . $ext;
        } else {
            $status = 0x2;
            $target_file = dirname(__DIR__) . '/dynamic/videos/' . $new . '.' . $ext;
        }
        if (move_uploaded_file($temp_name, $target_file)) {
            $sql->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags) VALUES (?,?,?,?,?,?,?,?)",
                [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), 'dynamic/videos/' . $new, $status]);

            if (!isset($noProcess)) {
                $storage->processVideo($new, $target_file);
            }

            redirect('./watch.php?v=' . $new);
        }
    } elseif (in_array(strtolower($ext), $supportedImageFormats, true)) {
        $storage->processImage($temp_name, $new);
        $status = 0x2;
        $sql->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type) VALUES (?,?,?,?,?,?,?,?,?)",
            [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), '/dynamic/art/' . $new . '.png', $status, 2]);
    
            redirect('./watch.php?v=' . $new);
        } else {
        error("415", "This file format is unsupported");
    }
}

$twig = twigloader();
echo $twig->render('upload.twig', [
    'limit' => (convertBytes(ini_get('upload_max_filesize'))),
]);
