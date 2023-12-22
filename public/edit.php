<?php

namespace openSB;

global $orange;
require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/SubmissionEdit.php';

if (isset($_POST['upload'])) {
    $id = ($_POST['vid_id'] ?? null);
} else {
    $id = ($_GET['v'] ?? null);
}

$page = new \Orange\Pages\SubmissionEdit($orange, $id);

if (isset($_POST['upload'])) {
    $page->postData($_POST);
}

$twig = new \Orange\Templating($orange);
echo $twig->render('edit.twig', [
    'data' => $page->getData(),
]);