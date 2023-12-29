<?php

namespace Orange;

global $orange;

use Orange\OrangeException;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/UserProfile.php';

$name = $path[2] ?? null;

if (isset($_GET['name'])) Utilities::redirect('/user/'.$_GET['name']);

try {
    $page = new \Orange\Pages\UserProfile($orange, $name);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new \Orange\Templating($orange);

echo $twig->render('profile.twig', [
    'data' => $data,
]);