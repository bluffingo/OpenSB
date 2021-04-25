<?php
require('lib/common.php');

// currently selects all uploaded videos, should turn it into all featured only

$error = "";
$success = '';

$twig = twigloader();

if(isset($_POST["loginsubmit"])){
	//check if user has inputed a username.
	if(empty(trim($_POST['username']))){
        $error .= 'Please enter your username! ';
    } else{
        $username = trim(htmlspecialchars($_POST['username']));
    }
	
	//check if user has inputed a password.
	if(empty(trim($_POST['password']))){
        $error .= 'Please enter your password! ';
    } else{
        $password = trim(htmlspecialchars($_POST['password']));
    }
	
	if(empty($error)) {
		$userData = query("SELECT * FROM users WHERE `username`='$username'");
		foreach ($userData as $row) {
			if(password_verify($password, $row['password'])){
				$success = true;
			} else {
				$success = false;
			}
		}
	}
}

echo $twig->render('login.twig', [
'error' => $error,
'success' => $success
]);