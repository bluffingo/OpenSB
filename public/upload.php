<?php

namespace squareBracket;

//this uploads and converts the video, should switch to a better solution!
require dirname(__DIR__) . '/private/class/common.php';

$supportedVideoFormats = ["mp4", "mkv", "wmv", "flv", "avi", "mov", "3gp"];
$supportedImageFormats = ["png", "jpg", "jpeg"];

//die(dirname(__DIR__) . '/private/scripts/processingworker.php');

if ($userbandata) {
    error(403, __("Banned user, can't continue."));
}

use PHLAK\StrGen;
use \Intervention\Image\ImageManager;

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $generator = new StrGen\Generator();
    $uploader = $userdata['id'];
    $new = $generator->alphaNumeric(11);

    $title = ($_POST['title'] ?? null);
    $description = ($_POST['desc'] ?? null);
    if ($isDebug) {
        $noProcess = ($_POST['debugUploaderSkip'] ?? null);
    }

    // Rate limit uploading to 2 minutes, both to prevent spam and to prevent double uploads.
    if ($sql->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 60 * 2, $userdata['id']]) && !$isDebug) {
        die(__("Please wait 2 minutes before uploading again."));
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
            $sql->query("INSERT INTO revisions (page, revision, time, author, type) VALUES (?,?,?,?,?)",
                [$new, 1, time(), $uploader, 1]);

            if (!isset($noProcess)) {
                if (substr(php_uname(), 0, 7) == "Windows") {
                    pclose(popen(sprintf('start /B  php %s "%s" "%s" > %s', dirname(__DIR__) . '\private\scripts\processingworker.php', $new, $target_file, dirname(__DIR__) . '/dynamic/videos/' . $new . '.log'), "r"));
                } else {
                    system(sprintf('php %s "%s" "%s" > %s 2>&1 &', dirname(__DIR__) . '/private/scripts/processingworker.php', $new, $target_file, dirname(__DIR__) . '/dynamic/videos/' . $new . '.log'));
                }
            }

            redirect('./watch.php?v=' . $new);
        }
    } elseif (in_array(strtolower($ext), $supportedImageFormats, true)) {
        $manager = new ImageManager();
        $target_file = dirname(__DIR__) . '/dynamic/art/' . $new . '.png';
        $target_thumbnail = dirname(__DIR__) . '/dynamic/art_thumbnails/' . $new . '.jpg';
        if (move_uploaded_file($temp_name, $target_file)) {
            $img = $manager->make($target_file);
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($target_file);
            $img = $manager->make($target_file)->encode('jpg', 80);
            $img->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($target_thumbnail);
        }
        $status = 0x2;
        $sql->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, post_type) VALUES (?,?,?,?,?,?,?,?,?)",
            [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), '/dynamic/art/' . $new . '.png', $status, 2]);
    } else {
        error("415", "This file format is unsupported");
    }
}

$twig = twigloader();
echo $twig->render('upload.twig', [
    'limit' => (convertBytes(ini_get('upload_max_filesize'))),
]);
