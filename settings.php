<?php
require('lib/common.php');
use Intervention\Image\ImageManager;

if (!$log) redirect('login.php');

$error = '';

if (isset($_POST['magic'])) {
	$title	= isset($_POST['title']) ? $_POST['title'] : null;
	$customcolor			= isset($_POST['customcolor']) ? $_POST['customcolor'] : '#3e3ecf'; // setting color to "null" would fuck up the scss compiler(?) -gr 7/26/2021
	$about			= isset($_POST['about']) ? $_POST['about'] : null;
	$signature		= isset($_POST['signature']) ? $_POST['signature'] : null;

	$language		= isset($_POST['language']) ? $_POST['language'] : 'en-US';

	$currentPass    = (isset($_POST['current_pass']) ? $_POST['current_pass'] : null);
	$pass           = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2          = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
	
	$theme          = isset($_POST['theme']) ? $_POST['theme'] : 'default';

	if ($currentPass && $pass && $pass2) {
		if (password_verify($currentPass, $userdata['password'])) {
			if ($pass == $pass2) {
				query("UPDATE users SET password = ?, token = ? WHERE id = ?",
					[password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $userdata['id']]);

				redirect('login.php?new_pass');
			} else {
				$error .= __(" The new passwords aren't identical.");
			}
		} else {
			$error .= __("Your current password is incorrect.");
		}
	}
	if ($error) $error = "The following errors occured while changing your password: ".$error;

	//  Code related to profile picture uploading
	if (isset($_FILES['profilePicture'])) {
		$manager = new ImageManager();
		$name       = $_FILES['profilePicture']['name'];
		$temp_name  = $_FILES['profilePicture']['tmp_name'];
		$ext  = pathinfo( $_FILES['profilePicture']['name'], PATHINFO_EXTENSION );
		$target_file = 'assets/profpic/' . $userdata['name'] . '.png';
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
		$backtarget_file = 'assets/backgrounds/' . $userdata['name'] . '.png';
		if (move_uploaded_file($backtemp_name, $backtarget_file)) {
			$img = $manager->make($backtarget_file);
			$img->save($backtarget_file, 0, 'png');
		}
	}

	query("UPDATE users SET title = ?, about = ?, customcolor = ?, language = ?, signature = ? WHERE id = ?",
		[$title, $about, $customcolor, $language, $signature, $userdata['id']]);
		
	setcookie('theme', $theme, 2147483647);
	

	if (!$error) {
		redirect(sprintf("user.php?name=%s&edited", $userdata['name']));
	}
}

$twig = twigloader();
echo $twig->render('settings.twig', [
	'error' => isset($error) ? $error : null
]);
