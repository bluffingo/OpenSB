<?php

namespace OpenSB;

global $orange;

use SquareBracket\Pages\SubmissionUpload;
use SquareBracket\Templating;

$page = new SubmissionUpload($orange);

if (isset($_POST['upload']) or isset($_POST['upload_video']) and $auth->isUserLoggedIn()) {
    $page->postData($_POST, $_FILES);
}

echo $twig->render('upload.twig');
