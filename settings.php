<?php
require('lib/common.php');
use Intervention\Image\ImageManager;

$manager = new ImageManager();

if (!$loggedIn) redirect('login.php');

if (isset($_POST['updatesettings'])) {
	$displayName	= isset($_POST['displayName']) ? $_POST['displayName'] : null;
	$description	= isset($_POST['description']) ? $_POST['description'] : null;
	$color			= isset($_POST['color']) ? $_POST['color'] : '#523bb8'; // setting color to "null" would fuck up the scss compiler(?) -gr 7/26/2021
	$language		= isset($_POST['language']) ? $_POST['language'] : 'en-US';
	
	$resetToken		= isset($_POST['reset_token']) ? $_POST['reset_token'] : null;
	
	$currentPass    = (isset($_POST['current_pass']) ? $_POST['current_pass'] : null);
	$pass           = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2          = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
	
	$error = "";
	if (!$currentPass) $error .= __("do not reset"); // Placeholder strings? -gr 7/26/2021
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
		// Back in PokTube there was a debate over if we should make profiles pictures use 1:1.
		// The result was to not resize strech profile pictures. That was back when PokTube
		// wasn't going to be a modern site (other than the Semantic UI bullshit). however
		// squareBracket is a modern site so any non-1:1 profile pictures should be strected 
		// to 1:1. there are some non-1:1 profile pictures being used on squarebracket but they 
		// came from PokTube user data getting migrated (included profile pictures). Just like 
		// how Twitter still keeps GIF profile pictures for those who haven't changed their profile
		// picture to a new static one. We should keep PokTube-migrated non-1:1 profile pictures.
		//                                                                 -Gamerappa, 7/26/2021
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
	query("UPDATE users SET display_name = ?, description = ?, color = ?, language = ? WHERE id = ?",
		[$displayName, $description, $color, $language, $currentUser['id']]);

	redirect(sprintf("user.php?name=%s&edited", $currentUser['username']));
}

$twig = twigloader();
if ($testNewLayout) {
	// COMPLETE (yes) revamp of the whole settings page, could make changing settings unusable so 
	// DEV ONLY -gr 7/26/2021 (holy shit the gamerappa name is 5 years old now)
	echo $twig->render('settings_redesign.twig'); 
} else {
	echo $twig->render('settings.twig');
}
