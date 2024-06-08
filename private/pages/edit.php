<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\SubmissionEdit;

if (isset($_POST['upload'])) {
    $id = ($_POST['vid_id'] ?? null);
} else {
    $id = ($_GET['v'] ?? null);
}

$page = new SubmissionEdit($orange, $id);

if (isset($_POST['upload'])) {
    $page->postData($_POST);
}

echo $twig->render('edit.twig', [
    'data' => $page->getData(),
]);