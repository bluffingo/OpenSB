<?php

namespace squareBracket;

require('lib/common.php');

if (isset($_POST['othermagic'])) {
    $language = isset($_POST['language']) ? $_POST['language'] : 'en-US';
    $theme = isset($_POST['theme']) ? $_POST['theme'] : 'default';
    $profilepicture = isset($_POST['profilepicture']) ? $_POST['profilepicture'] : 'circle';

    setcookie('language', $language, 2147483647);
    setcookie('theme', $theme, 2147483647);
    setcookie('profilepicture', $profilepicture, 2147483647);


    //if (!$error) {
    redirect(sprintf("index.php?updated=true", isset($userdata['name']) ? $userdata['name'] : null));
    //}
}

$twig = twigloader();
echo $twig->render('modal.twig');