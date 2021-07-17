<?php
$rawOutputRequired = true;
require('lib/common.php');

if (!isset($_POST['subscription']) or $_POST['subscription'] == '') {
	die(); //don't output anything if this sneaky bastard didn't put anything to the comment field
}
if (result("SELECT COUNT(user) FROM subscriptions WHERE user=?", [$_POST['subscription']]) != 0) {
	query("DELETE FROM subscriptions WHERE user=?", [$_POST['subscription']]);
	echo __("Subscribe");
} else {	
	query("INSERT INTO subscriptions (id, user) VALUES (?,?)",
		[$currentUser['id'],$_POST['subscription']]);
	echo __("Unsubscribe");
}
