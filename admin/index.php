<?php
namespace squareBracket\Admin;
require('lib/common.php');

if ($userdata['powerlevel'] < 3) \squareBracket\error('403', "You shouldn't be here, get out!");

//$memcachedStats = $cache->memcached->getStats();

$latestRegisteredUsers = \squareBracket\query("SELECT id, name, customcolor, joined FROM users ORDER BY joined DESC LIMIT 7");
$latestSeenUsers = \squareBracket\query("SELECT id, name, customcolor, lastview FROM users ORDER BY lastview DESC LIMIT 7");

$thingsToCount = ['comments', 'channel_comments', 'users', 'videos', 'rating', 'subscriptions', 'views'];

$query = "SELECT ";
foreach ($thingsToCount as $thing) {
	if ($query != "SELECT ") $query .= ", ";
	$query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
}
$count = \squareBracket\fetch($query);

$latestComments = \squareBracket\query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id ORDER BY c.date DESC LIMIT 7");

$twig = \squareBracket\twigloader();
echo $twig->render('admin/index.twig', [
	'latest_registered_users' => $latestRegisteredUsers,
	'latest_seen_users' => $latestSeenUsers,
	'things_to_count' => $thingsToCount,
	'count' => $count,
	'latest_comments' => $latestComments
]);
