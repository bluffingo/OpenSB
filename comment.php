<?php
require('lib/common.php');

if (!isset($_POST['vidid'])) {
	die(__("No POST data."));
} else if (!isset($_POST['vidid']) or $_POST['vidid'] == '') {
	die(); //don't output anything if there is no data.
}

$comment = [
	'u_username' => $userdata['name'],
	'comment' => $_POST['comment'],
	'date' => time()
];

query("INSERT INTO comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
	[$_POST['vidid'],$_POST['comment'],$userdata['id'],time(),0]);

$twig = twigloader();
echo $twig->render('components/comment.twig', [
	'data' => $comment
]);