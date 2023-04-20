#!/usr/bin/php
<?php

print("principia-web tools - hash-emails.php\n");
print("=========================================\n");
print("Hashes emails in a database with unhashed emails.\n");

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$users = query("SELECT id, email FROM users");

while ($user = $users->fetch()) {
	$id = $user['id'];
	$emailhash = mailhash($user['email']);

	query("UPDATE users SET email = ? WHERE id = ?", [$emailhash, $id]);
}