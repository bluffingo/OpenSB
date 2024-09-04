<?php

namespace OpenSB;

global $auth, $twig, $database, $orange;

use SquareBracket\Utilities;

if (!$auth->isUserAdmin()) {
    Utilities::bannerNotification("You do not have permission to access this page.", "/");
}

if (!$auth->hasUserAuthenticatedAsAnAdmin()) {
    Utilities::bannerNotification("Please login with your admin password.", "/admin/login");
}

if ($orange->getLocalOptions()["skin"] != "biscuit" && $orange->getLocalOptions()["skin"] != "charla") {
    Utilities::bannerNotification("Please change your skin to Biscuit.", "/theme");
}

echo $twig->render("admin_temporary.twig");