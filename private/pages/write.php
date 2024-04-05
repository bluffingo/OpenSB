<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\JournalWrite;
use SquareBracket\Templating;

$page = new JournalWrite($orange);

if (isset($_POST['upload']) or isset($_POST['upload_video']) and isset($userdata['name'])) {
    $page->postData($_POST, $_FILES);
}

echo $twig->render('write.twig');
