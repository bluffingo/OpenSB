<?php

namespace OpenSB;

global $orange;

use Orange\OrangeException;
use Orange\Templating;
use Orange\Utilities;
use Orange\Pages\UserProfile;

$name = $path[2] ?? null;

if (isset($_GET['name'])) Utilities::redirect('/user/'.$_GET['name']);

$page = new UserProfile($orange, $name);
$data = $page->getData();

$twig = new Templating($orange);

echo $twig->render('profile.twig', [
    'data' => $data,
]);