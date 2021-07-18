<?php
require('lib/common.php');

if (!isset($_POST['vidid'])) {
	die('No data.');
} else if (!isset($_POST['comment']) or $_POST['comment'] == '') {
	die(); //don't output anything if there is no data.
}

$comment = [
	'u_username' => $currentUser['username'],
	'comment' => $_POST['comment'],
	'date' => time()
];

query("INSERT INTO comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
	[$_POST['vidid'],$_POST['comment'],$currentUser['id'],time(),0]);

$twig = twigloader();
echo $twig->render('components/comment.twig', [
	'data' => $comment
]);