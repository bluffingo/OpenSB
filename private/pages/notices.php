<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\CoreException;
use SquareBracket\Pages\AccountNotifications;

$page = new AccountNotifications($orange);
$data = $page->getData();

echo $twig->render('portal.twig', [
    'data' => $data,
]);