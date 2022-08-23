<?php

namespace squareBracket;

//this uploads and converts the video, should switch to a better solution!
require dirname(__DIR__) . '/private/class/common.php';

use PHLAK\StrGen;

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
	$generator = new StrGen\Generator();
    $uploader = $userdata['id'];
    $new = $generator->alphaNumeric(11);

    $title = ($_POST['title'] ?? null);
    $description = ($_POST['desc'] ?? null);
    if($isDebug) {
        $noProcess = ($_POST['debugUploaderSkip'] ?? null);
    }

    // Rate limit uploading to 2 minutes, both to prevent spam and to prevent double uploads.
    if ($sql->result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 60 * 2, $userdata['id']]) && !$isDebug) {
        die(__("Please wait 2 minutes before uploading again. If you've already uploaded a video, it is (probably) being processed."));
    }

    $name = $_FILES['fileToUpload']['name'];
    $temp_name = $_FILES['fileToUpload']['tmp_name']; // gets video info and thumbnail info
    $ext = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
    if(isset($noProcess) && $isDebug) {
        $status = 0x0; // pretend that video has been successfully uploaded
        $target_file = dirname(__DIR__) . '/dynamic/videos/' . $new . '.converted.' . $ext;
    } else {
        $status = 0x2;
        $target_file = dirname(__DIR__) . '/dynamic/videos/' . $new . '.' . $ext;
    }
    if (move_uploaded_file($temp_name, $target_file)) {
        $sql->query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags) VALUES (?,?,?,?,?,?,?,?)",
            [$new, $title, $description, $uploader, time(), json_encode(explode(', ', $_POST['tags'])), 'dynamic/videos/' . $new, $status]);

        if(!isset($noProcess)) {
            if (substr(php_uname(), 0, 7) == "Windows") {
                pclose(popen(sprintf('start /B  php lib/scripts/processingworker.php "%s" "%s" > %s', $new, $target_file, 'videos/' . $new . '.log'), "r"));
            } else {
                system(sprintf('php lib/scripts/processingworker.php "%s" "%s" > %s 2>&1 &', $new, $target_file, 'videos/' . $new . '.log'));
            }
        }

        redirect('./watch.php?v=' . $new);
    }
}

$twig = twigloader();
echo $twig->render('upload.twig');
