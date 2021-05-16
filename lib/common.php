<?php
if (!file_exists('conf/config.php')) {
	die('Fatal squareBracket Error: Please read the installing instructions in the README file.');
}

require('conf/config.php');
require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}

$userfields = userfields();

// Cookie auth
if (isset($_COOKIE['SBTOKEN'])) {
	$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE['SBTOKEN']]);

	if ($id) {
		// Valid cookie, logged in
		$loggedIn = true;
	} else {
		// Invalid cookie, not logged in
		$loggedIn = false;
	}
} else {
	// No cookie, not logged in
	$loggedIn = false;
}

// Theme selector stuff
if (isset($_COOKIE['theme'])) {
	$theme = $_COOKIE['theme'];
} else {
	// No cookie, default to the default theme
	$theme = 'default';
}

if ($loggedIn) {
	$currentUser = fetch("SELECT * FROM users WHERE id = ?", [$id]);
}
// put any default settings here as they get added.
if ($isDebug) {
	$dat = getrusage();
	if(!isset($rawOutputRequired) OR !$rawOutputRequired) ?>
		<div class="offcanvas offcanvas-bottom show" data-bs-scroll="true" data-bs-backdrop="false" style="visibility: visible; height: unset;">
		  <div class="offcanvas-body small">
			<?php printf('debug: logged in as %s, timings: user time used: %s system time used: %s, current locale: %s', (isset($currentUser['username']) ? $currentUser['username'] : 'not logged in'), $dat["ru_utime.tv_sec"], $dat["ru_stime.tv_sec"], (isset($currentUser['language']) ? $currentUser['language'] : 'en_US')); ?>
		  </div>
		</div>
	<?php
}

$lang = new Lang(sprintf("lib/lang/".(isset($currentUser['language']) ? $currentUser['language'] : 'en_US').".json"));