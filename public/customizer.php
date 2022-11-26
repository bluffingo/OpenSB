<?php

namespace openSB;

require dirname(__DIR__) . '/private/class/common.php';

if (isset($_POST['othermagic'])) {
    $language = $_POST['language'] ?? 'en-US';
    $theme = $_POST['theme'] ?? 'default';
    $profilepicture = $_POST['profilepicture'] ?? 'circle';
    $enableSounds = $_POST['enableSounds'] ?? false;

    setcookie('language', $language, 2147483647);
    setcookie('theme', $theme, 2147483647);
    setcookie('profilepicture', $profilepicture, 2147483647);
    setcookie('SBSOUNDS', $enableSounds, 2147483647);

    //if (!$error) {
    redirect(sprintf("index.php?updated=true", isset($userdata['name']) ? $userdata['name'] : null));
    //}
}

$twig = twigloader();
echo $twig->render('modal.twig');