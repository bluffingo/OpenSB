<?php
require('lib/common.php');
use Intervention\Image\ImageManager;

$manager = new ImageManager();

if (!$loggedIn) redirect('login.php');

if (isset($_POST['updatesettings'])) {
	$description	= $_POST['description'] ? $_POST['description'] : null;
	$color			= $_POST['color'] ? $_POST['color'] : null;
	$language		= $_POST['language'] ? $_POST['language'] : 'en_US';
	
	$name       = $_FILES['profilePicture']['name'];
	$temp_name  = $_FILES['profilePicture']['tmp_name'];
	$ext  = pathinfo( $_FILES['profilePicture']['name'], PATHINFO_EXTENSION );
	$target_file = 'assets/profpic/' . $currentUser['username'] . '.png';
	if (move_uploaded_file($temp_name, $target_file)){
		$img = $manager->make($target_file);
		$img->resize(640, 640);
		$img->save($target_file);
	}
	$backname       = $_FILES['profileBackground']['name'];
	$backtemp_name  = $_FILES['profileBackground']['tmp_name'];
	$backext  = pathinfo( $_FILES['profileBackground']['name'], PATHINFO_EXTENSION );
	$backtarget_file = 'assets/backgrounds/' . $currentUser['username'] . '.png';
	if (move_uploaded_file($backtemp_name, $backtarget_file))
	query("UPDATE users SET description = ?, color = ?, language = ? WHERE id = ?",
		[$description, $color, $language, $currentUser['id']]);

	redirect(sprintf("user.php?name=%s&edited", $currentUser['username']));
}

$twig = twigloader();
echo $twig->render('settings.twig');
