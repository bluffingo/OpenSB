<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

if($loggedIn) {
	if(isset($_POST['description']) AND $_POST['description'] != '') {
		query("UPDATE `users` SET `description`= ? WHERE `username`= ?", [$_POST['description'], $currentUser['username']]);
	} 
	if(isset($_POST['color']) AND $_POST['color'] != '#000000') {
		query("UPDATE `users` SET `color`= ? WHERE `username`= ?", [$_POST['color'], $currentUser['username']]);
	} 
	if(count($_POST) == 0) {
		$twig = twigloader();
		echo $twig->render('settings.twig');
	}
} else {
	redirect('./login.php');
}
