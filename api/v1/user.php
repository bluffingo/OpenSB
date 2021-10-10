<?php
chdir('../../');
$rawOutputRequired = true;
require('lib/common.php');

header('Content-Type: application/json');

$username = (isset($_GET['name']) ? $_GET['name'] : null);

$userData = fetch("SELECT * FROM users WHERE name = ?", [$username]);

if (!$userData) {
	$apiOutput = [ 'error' => "No user specified or invalid video ID", 'code' => "52e44102" ];

	echo json_encode($apiOutput);
	die();
}

// TODO: comments? likes?
$apiOutput = [
	'id'	=> $userData['id'],
	'username'	=> $userData['username'],
	'joinDate' => $userData['joined'],
	'lastConnection' => $userData['lastview'],
	'profileColor' => $userData['color'],
	'description' => $userData['description'],
];

echo json_encode($apiOutput);
