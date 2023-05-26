<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

/**
 * Hashes email addresses.
 *
 * @since principia-web (ported in openSB Beta 3.0)
 *
 * @param $email
 * @return string
 */
function mailHash($email) {
	global $emailsalt;
	return hash('sha256', $emailsalt . $email);
}

/**
 *
 * Verifies if the inputted email's hash is identical to the email's hash in the database.
 *
 * @since principia-web (ported in openSB Beta 3.0)
 *
 * @param $email
 * @param $hash
 * @return bool
 */
function mailVerify($email, $hash) {
	return mailHash($email) == $hash;
}