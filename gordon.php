<?php
include('lib/common.php');
/* GORDON: sbnext login/register page
don't backport this into bootstrap, not worth the effort.

-gamerappa 7/10/2021
*/

$error = '';

/* REGISTER  */

if (isset($_POST['action'])) {
	$username = (isset($_POST['username']) ? $_POST['username'] : null);
	$email = (isset($_POST['email']) ? $_POST['email'] : null);
	$pass = (isset($_POST['pass1']) ? $_POST['pass1'] : null);
	$pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);

	if (!isset($username)) $error .= __("Blank username. ");
	if (!isset($email)) $error .= __("Blank email. ");
	if (!isset($pass) || strlen($pass) < 6) $error .= __("Password is too short. ");
	if (!isset($pass2) || $pass != $pass2) $error .= __("The passwords don't match. ");
	if (result("SELECT COUNT(*) FROM users WHERE username = ?", [$username])) $error .= __("Username has already been taken. ");
	if (!preg_match('/[a-zA-Z0-9_]+$/', $username)) $error .= __("Username contains invalid characters (Only alphanumeric and underscore allowed). ");
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error .= __("Email isn't valid. ");

	if ($error == '') {
		$token = bin2hex(random_bytes(32));
		query("INSERT INTO users (username, password, email, token, joined) VALUES (?,?,?,?,?)",
			[$username,password_hash($pass, PASSWORD_DEFAULT), $email, $token, time()]);

		setcookie('SBTOKEN', $token, 2147483647);

		redirect('./');
	}
}

/* LOGIN  */

if (isset($_POST["loginsubmit"])) {
	$username = (isset($_POST['username']) ? $_POST['username'] : null);
	$password = (isset($_POST['password']) ? $_POST['password'] : null);

	// Check to see if the user actually has entered anything.
	if (!$username)	$error = __("Please enter your username! ");
	if (!$password) $error = __("Please enter your password! ");

	if (empty($error)) {
		$logindata = fetch("SELECT password,token FROM users WHERE username = ?", [$username]);
		if ($logindata && password_verify($password, $logindata['password'])) {
			setcookie('SBTOKEN', $logindata['token'], 2147483647);

			redirect('./');
		} else {
			$error = __("Incorrect username or password.");
		}
	}
}

$twig = twigloader();
echo $twig->render('gordon.twig', ['error' => $error]);