<?php

namespace OpenSB;

global $orange;

use Orange\Templating;
use Orange\Pages\JournalWrite;

$page = new JournalWrite($orange);

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $page->postData($_POST, $_FILES);
}

$twig = new Templating($orange);

echo $twig->render('write.twig');
