<?php
require('lib/common.php');

if (!$loggedIn) redirect('login.php');

if (isset($_POST['updatesettings'])) {
	$description	= $_POST['description'] ? $_POST['description'] : null;
	$color			= $_POST['color'] ? $_POST['color'] : null;
	$language		= $_POST['language'] ? $_POST['language'] : 'en_US';

	query("UPDATE users SET description = ?, color = ?, language = ? WHERE id = ?",
		[$description, $color, $language, $currentUser['id']]);

	redirect(sprintf("user.php?name=%s&edited", $currentUser['username']));
}

$twig = twigloader();
echo $twig->render('settings.twig');
