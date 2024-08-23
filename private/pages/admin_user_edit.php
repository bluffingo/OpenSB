<?php

namespace OpenSB;

global $auth, $twig, $database, $orange, $path;

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

$username = $path[3] ?? null;

$user = $database->fetch("SELECT * FROM users u WHERE u.name = ?", [$username]);

$users_with_matching_ips = $database->fetchArray($database->query("SELECT u.name, u.title FROM users u WHERE u.ip = ? AND id != ?",
    [$user["ip"], $user["id"]]));

echo $twig->render('admin_user_edit.twig', [
    'user' => $user,
    'users_with_matching_ips' => $users_with_matching_ips,
]);