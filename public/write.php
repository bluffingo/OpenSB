<?php

namespace openSB;

global $betty;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/JournalWrite.php';

$page = new \Orange\Pages\JournalWrite($betty);

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $page->postData($_POST, $_FILES);
}

$twig = new Templating($betty);

echo $twig->render('write.twig');
