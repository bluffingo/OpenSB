<?php

namespace openSB;

global $betty;
require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/SubmissionEdit.php';

if (isset($_POST['upload'])) {
    $id = ($_POST['vid_id'] ?? null);
} else {
    $id = ($_GET['v'] ?? null);
}

$page = new \Betty\Pages\SubmissionEdit($betty, $id);

if (isset($_POST['upload'])) {
    $page->post($_POST);
}

$twig = new \Betty\Templating($betty);
echo $twig->render('edit.twig', [
    'data' => $page->getData(),
]);