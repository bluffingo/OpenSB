<?php

namespace OpenSB;

global $orange;

use Orange\Templating;
use Orange\Pages\SubmissionEdit;

if (isset($_POST['upload'])) {
    $id = ($_POST['vid_id'] ?? null);
} else {
    $id = ($_GET['v'] ?? null);
}

$page = new SubmissionEdit($orange, $id);

if (isset($_POST['upload'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);
echo $twig->render('edit.twig', [
    'data' => $page->getData(),
]);