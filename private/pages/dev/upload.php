<?php

namespace OpenSB;

global $auth, $twig, $database, $orange, $isDebug;

use SquareBracket\Utilities;

if (!$isDebug) {
    Utilities::redirect("/upload");
}

if ($orange->getLocalOptions()["skin"] != "charla") {
    Utilities::bannerNotification("Please change your skin to Charla.", "/theme");
}

echo $twig->render('upload_new.twig', [
    'limit' => (Utilities::convertBytes(ini_get('upload_max_filesize'))),
]);;
