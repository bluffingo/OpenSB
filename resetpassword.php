<?php
require('lib/common.php');

$id = (isset($_GET['id']) ? $_GET['id'] : null);

if (isset($_GET['grf']) && $userdata['powerlevel'] > 2) {
	$generateResetFor = (isset($_GET['grf']) ? $_GET['grf'] : null);

	if ($generateResetFor) {
		$token = bin2hex(random_bytes(32));

		query("INSERT INTO passwordresets (id, user, time) VALUES (?,?,?)",
			[$token, $generateResetFor, time()]);

		// TODO: make this a bit more easy to use
		printf("/resetpassword.php?id=%s", $token);
	}
	die();
}

$resetdata = fetch("SELECT pr.*, u.name FROM passwordresets pr JOIN users u ON pr.user = u.id WHERE pr.id = ?", [$id]);

if (!$resetdata) die("<center><b>" . __("No reset data.") . "</b></center>");
// TODO: we need a nice error page template
if ((time() - $resetdata['time']) >= 60*15) die("<center><b>Password reset request expired.</b></center>");
if (!$resetdata['active']) die("<center><b>Your password has already been reset by this request.</b></center>");

$error = '';

if (isset($_POST['action'])) {
	$pass = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);

	if ($pass != $pass2) $error .= "Passwords aren't identical.";

	if ($error == '') {
		query("UPDATE users SET password = ?, token = ? WHERE id = ?",
			[password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $resetdata['user']]);
		query("UPDATE passwordresets SET active = 0 WHERE id = ?", [$id]);

		redirect('login.php?resetted');
	}
}

$twig = twigloader();
echo $twig->render('resetpassword.twig', [
	'resetdata' => $resetdata,
	'error' => $error
]);