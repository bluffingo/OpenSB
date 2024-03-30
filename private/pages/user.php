<?php

namespace OpenSB;

global $orange;

use Core\Utilities as UtilitiesAlias;
use SquareBracket\Pages\UserProfile;
use SquareBracket\Templating;

$name = $path[2] ?? null;

if (isset($_GET['name'])) UtilitiesAlias::redirect('/user/' . $_GET['name']);

$page = new UserProfile($orange, $name);
$data = $page->getData();

$twig = new Templating($orange);

echo $twig->render('profile.twig', [
    'data' => $data,
]);