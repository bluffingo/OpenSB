#!/usr/bin/php
<?php
namespace openSB;

print("principia-web tools - hash-emails.php\n");
print("=========================================\n");
print("Hashes emails in a database with unhashed emails.\n");

require_once dirname(__DIR__) . '/private/class/common.php';

$users = $sql->query("SELECT id, email FROM users");

while ($user = $users->fetch()) {
	$id = $user['id'];
	$emailhash = mailhash($user['email']);

	$sql->query("UPDATE users SET email = ? WHERE id = ?", [$emailhash, $id]);
}
