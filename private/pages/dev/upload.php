<?php

namespace OpenSB;

global $auth, $twig, $database, $orange, $isDebug;

use SquareBracket\UnorganizedFunctions;

if (!$isDebug) {
    UnorganizedFunctions::redirect("/upload");
}

if ($orange->getLocalOptions()["skin"] != "charla") {
    UnorganizedFunctions::bannerNotification("Please change your skin to Charla.", "/theme");
}

echo $twig->render('upload_new.twig', [
    'limit' => (UnorganizedFunctions::convertBytes(ini_get('upload_max_filesize'))),
]);;
