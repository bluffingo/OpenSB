<?php

namespace OpenSB;

global $orange;

use SquareBracket\Pages\UserProfile;
use SquareBracket\Templating;
use SquareBracket\UnorganizedFunctions;

$name = $path[2] ?? null;

if (isset($_GET['name'])) UnorganizedFunctions::redirect('/user/' . $_GET['name']);

$page = new UserProfile($orange, $name);
$data = $page->getData();

$twig = new Templating($orange);

echo $twig->render('profile.twig', [
    'data' => $data,
]);