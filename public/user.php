<?php

namespace openSB;

global $orange;

use Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/UserProfile.php';

$id = ($_GET['name'] ?? null);

try {
    $page = new \Orange\Pages\UserProfile($orange, $id);
    $data = $page->getData();
} catch (OrangeException $e) {
    $e->page();
}

$twig = new \Orange\Templating($orange);

echo $twig->render('profile.twig', [
    'data' => $data,
]);