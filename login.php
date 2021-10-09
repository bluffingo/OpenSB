<?php
require('lib/common.php');

$error = '';

if (isset($_POST["loginsubmit"])) {
	$username = (isset($_POST['username']) ? $_POST['username'] : null);
	$password = (isset($_POST['password']) ? $_POST['password'] : null);

	// Check to see if the user actually has entered anything.
	if (!$username)	$error = __("Please enter your username! ");
	if (!$password) $error = __("Please enter your password! ");

	if (empty($error)) {
		$logindata = fetch("SELECT password,token FROM users WHERE name = ?", [$username]);
		if ($logindata && password_verify($password, $logindata['password'])) {
			setcookie('SBTOKEN', $logindata['token'], 2147483647);

			redirect('./');
		} else {
			$error = __("Incorrect username or password.");
		}
	}
}

$twig = twigloader();

echo $twig->render('login.twig', [
	'error' => $error,
	'resetted' => isset($_GET['resetted']) ? true : false,
	'new_pass' => isset($_GET['new_pass']) ? true : false,
	'new_token' => isset($_GET['new_token']) ? true : false,
]);