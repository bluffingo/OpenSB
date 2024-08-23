<?php

namespace OpenSB;

global $auth, $twig, $database, $orange;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page.", "/");
}

if (!$auth->hasUserAuthenticatedAsAnAdmin()) {
    UnorganizedFunctions::Notification("Please login with your admin password.", "/admin/login");
}

if ($orange->getLocalOptions()["skin"] != "biscuit" && $orange->getLocalOptions()["skin"] != "charla") {
    UnorganizedFunctions::Notification("Please change your skin to Biscuit.", "/theme");
}

echo $twig->render("admin_temporary.twig");