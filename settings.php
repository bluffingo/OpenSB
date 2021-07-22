<?php
require('lib/common.php');
use Intervention\Image\ImageManager;

$manager = new ImageManager();

if (!$loggedIn) redirect('login.php');

if (isset($_POST['updatesettings'])) {
	$description	= isset($_POST['description']) ? $_POST['description'] : null;
	$color			= isset($_POST['color']) ? $_POST['color'] : null;
	$language		= isset($_POST['language']) ? $_POST['language'] : 'en_US';
	
	$resetToken		= isset($_POST['reset_token']) ? $_POST['reset_token'] : null;
	
	$currentPass    = (isset($_POST['current_pass']) ? $_POST['current_pass'] : null);
	$pass           = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2          = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
	
	$error = "";
	if (!$currentPass) $error .= __("do not reset");
	if (!$pass) $error .= __("do not reset");
	if (!$pass2) $error .= __("do not reset");
	if ($pass != $pass2) $error .= __("Passwords aren't identical.");
	
	$logindata = fetch("SELECT password FROM users WHERE id = ?", [$currentUser['id']]);
	if ($logindata && password_verify($currentPass, $logindata['password'])) {
		if ($error == '') {
			query("UPDATE users SET password = ?, token = ? WHERE id = ?", 
				[password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $currentUser['id']]);

			redirect('login.php?new_pass');
		}
	}
	
	if($resetToken) {
		query("UPDATE users SET token = ? WHERE id = ?", [bin2hex(random_bytes(32)), $currentUser['id']]);
		redirect('login.php?new_token');
	}
	
	$name       = $_FILES['profilePicture']['name'];
	$temp_name  = $_FILES['profilePicture']['tmp_name'];
	$ext  = pathinfo( $_FILES['profilePicture']['name'], PATHINFO_EXTENSION );
	$target_file = 'assets/profpic/' . $currentUser['username'] . '.png';
	if (move_uploaded_file($temp_name, $target_file)){
		$img = $manager->make($target_file);
		$img->resize(640, 640);
		$img->save($target_file, 0, 'png');
	}
	$backname       = $_FILES['profileBackground']['name'];
	$backtemp_name  = $_FILES['profileBackground']['tmp_name'];
	$backext  = pathinfo( $_FILES['profileBackground']['name'], PATHINFO_EXTENSION );
	$backtarget_file = 'assets/backgrounds/' . $currentUser['username'] . '.png';
	if (move_uploaded_file($backtemp_name, $backtarget_file)) {
		$img = $manager->make($backtarget_file);
		$img->save($backtarget_file, 0, 'png');
	}
	query("UPDATE users SET description = ?, color = ?, language = ? WHERE id = ?",
		[$description, $color, $language, $currentUser['id']]);

	redirect(sprintf("user.php?name=%s&edited", $currentUser['username']));
}

$twig = twigloader();
echo $twig->render('settings.twig');
