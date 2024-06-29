<?php

namespace OpenSB;

global $auth, $twig, $database, $orange;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page", "/");
}

if ($orange->getLocalOptions()["skin"] != "biscuit") {
    UnorganizedFunctions::Notification("Please change your skin to Biscuit.", "/theme");
}

echo $twig->render("admin_temporary.twig");