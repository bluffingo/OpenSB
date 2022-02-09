<?php
include('lib/common.php');

$error = '';

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
	$username = (isset($_POST['username']) ? $_POST['username'] : null);
	$pass = (isset($_POST['pass1']) ? $_POST['pass1'] : null);
	$pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
	$displayName = (isset($_POST['displayName']) ? $_POST['displayName'] : null);

	if (!isset($username)) $error .= __("Blank username.");
	if (!isset($pass) || strlen($pass) < 6) $error .= __("Password is too short.");
	if (!isset($pass2) || $pass != $pass2) $error .= __("The passwords don't match.");
	if (!isset($displayName)) $error .= __("Blank display name.");
	if (result("SELECT COUNT(*) FROM users WHERE name = ?", [$username])) $error .= __("Username has already been taken. "); //ashley2012 bypassed this -gr 7/26/2021
	if (!preg_match('/[a-zA-Z0-9_]+$/', $username)) $error .= __("Username contains invalid characters (Only alphanumeric and underscore allowed)."); //ashley2012 bypassed this with the long-ass arabic character. -gr 7/26/2021
	// hCaptcha verification (commenting this out due to the fact that site has no hcap)
	// if ($hCaptchaSiteKey && $hCaptchaSecret) {
	//	if (!hCaptcha($_POST['h-captcha-response'])) $error .= __("Incorrect CAPTCHA. Please try again.");
	//}

	if ($error == '') {
		$token = bin2hex(random_bytes(32));
			query("INSERT INTO users (name, password, token, joined, title) VALUES (?,?,?,?,?)",
			[$username,password_hash($pass, PASSWORD_DEFAULT), $token, time(), $displayName]);
			
			$newUser = result("SELECT `id` from `users` where `name` = ?",[$username]);
			
			fetch("INSERT INTO `channel_settings` 
			(`user`, `background`, `fontcolor`, `titlefont`, `link`, `headerfont`, `highlightheader`, `highlightinside`, `regularheader`, `regularinside`) 
			VALUES (?, '#ffffff', '#222222', '#ffffff', '#0033CC', '#ffffff', '#3399cc', '#ecf4fb', '#3399cc', '#ffffff')",[$newUser]);

		setcookie('SBTOKEN', $token, 2147483647);

		redirect('./');
	}
}

/////////////////////////////////////////////////////////////////////////////////// ARCHIVE
// DEV ONLY - display names aren't currently fully implemented!!!!!!!!!!
// ok yes i know this would break sbnext (though i deleted gordon.php since
// it could have been used to bypass hcaptcha registeration and was a placeholder) 
// but the only person who remotely even gives a shit about sbnext is icanttellyou 
// but he's only using it as an excuse of not having finalium become the default theme 
// for squarebracket. this excuse is invalid as he makes excuses for not working on sbnext.
//
// sbnext is NOT the future of squarebracket, the only thing implemented is a main page and 
// (SUPER IMCOMPLETE) watch page with super garbage css. that's somehow worse than the
// fucking css used on subrocks (bhief why are you using a homestuck stan to fix your
// shitty css INSTEAD OF STEALING GOOGLE'S OLD-ASS CSS FOR YOUR REVIVAL). if there's
// no progress on sbnext by beta 2 (or beta 1 refresh if we're doing the milestone 2/alpha 3 
// cycle bullshit) then i will scrub sbnext off the squarebracket codebase. i am not sorry.
// 
// -Gamerappa, july 26th, 2021, 11:11PM EST.
///////////////////////////////////////////////////////////////////////////////////////////

$twig = twigloader();
echo $twig->render('register.twig', ['error' => $error]);
