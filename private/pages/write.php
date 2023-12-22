<?php

namespace Orange;

global $orange;

use Orange\Templating;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/JournalWrite.php';

$page = new \Orange\Pages\JournalWrite($orange);

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $page->postData($_POST, $_FILES);
}

$twig = new Templating($orange);

echo $twig->render('write.twig');
