<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

function mailHash($email) {
	global $emailsalt;
	return hash('sha256', $emailsalt . $email);
}

function mailVerify($email, $hash) {
	return mailHash($email) == $hash;
}