<?php
require('lib/common.php');

$action = (isset($_POST['action']) ? $_POST['action'] : null);

needsLogin();

$topbot = [
	'breadcrumb' => [['href' => './', 'title' => 'Main'], ['href' => "private.php", 'title' => __("Private messages")]],
	'title' => 'Send'
];

if (!hasPerm('create-pms')) error('403', __("You have no permissions to do this!"));

$error = '';

// Submitting a PM
if ($action == 'Submit') {
	$userto = result("SELECT id FROM users WHERE name LIKE ?", [$_POST['userto']]);

	if ($userto && $_POST['message']) {
		$recentpms = fetch("SELECT date FROM z_pmsgs WHERE date >= (UNIX_TIMESTAMP() - 30) AND userfrom = ?", [$userdata['id']]);
		if ($recentpms) {
			$error = __("You can't send more than one PM within 30 seconds!");
		} else {
			query("INSERT INTO z_pmsgs (date,userto,userfrom,title,text) VALUES (?,?,?,?,?)",
				[time(),$userto,$userdata['id'],$_POST['title'],$_POST['message']]);
			$nextId = insertId();

			query("INSERT INTO notifications (type, level, recipient, sender) VALUES (?,?,?,?)",
				[3, $nextId, $userto, $userdata['id']]);

			redirect("private.php");
		}
	} elseif (!$userto) {
		$error = __("That user doesn't exist!");
	} elseif (!$_POST['message']) {
		$error = __("You can't send a blank message!");
	}
}

$userto = (isset($_POST['userto']) ? $_POST['userto'] : '');
$title = (isset($_POST['title']) ? $_POST['title'] : '');
$quotetext = (isset($_POST['message']) ? $_POST['message'] : '');

// Default
if (!$action) {
	if (isset($_GET['pid']) && $pid = $_GET['pid']) {
		$post = fetch("SELECT u.name name, p.title, p.text "
			."FROM z_pmsgs p LEFT JOIN users u ON p.userfrom = u.id "
			."WHERE p.id = ?" . (!hasPerm('view-user-pms') ? " AND (p.userfrom=".$userdata['id']." OR p.userto=".$userdata['id'].")" : ''), [$pid]);
		if ($post) {
			$quotetext = sprintf(
				'[reply="%s" id="%s"]%s[/reply]'.PHP_EOL.PHP_EOL,
			$post['name'], $pid, $post['text']);

			$title = __("Re:") . $post['title'];
			$userto = $post['name'];
		}
	}

	if (isset($_GET['uid']) && $uid = $_GET['uid']) {
		$userto = result("SELECT name FROM users WHERE id = ?", [$uid]);
	} elseif (!isset($userto)) {
		$userto = $_POST['userto'];
	}
} else if ($action == __("Preview")) { // Previewing PM
	$post['date'] = time();
	$post['text'] = $_POST['message'];
	foreach ($userdata as $field => $val)
		$post['u' . $field] = $val;
	$post['ulastpost'] = time();

	$userto = $_POST['userto'];
	$title = $_POST['title'];
	$quotetext = $_POST['message'];
	$topbot['title'] .= __(" (Preview)");
}

$twig = _twigloader();
echo $twig->render('forum/sendprivate.twig', [
	'post' => (isset($post) ? $post : null),
	'userto' => $userto,
	'title' => $title,
	'quotetext' => $quotetext,
	'topbot' => $topbot,
	'action' => $action,
	'error' => $error
]);
