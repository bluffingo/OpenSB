<?php

function editThread($id, $title = '', $forum = 0, $closed= -1, $sticky= -1, $delete = -1) {
	if ($delete < 1) {
		$set = '';
		if ($title != '') $set .= ",title='$title'";
		if ($closed >= 0) $set .= ",closed=$closed";
		if ($sticky >= 0) $set .= ",sticky=$sticky";
		$set[0] = ' ';
		if (strlen(trim($set))>0&&!is_array($set)) query("UPDATE z_threads SET $set WHERE id = ?", [$id]);

		if ($forum)
			moveThread($id,$forum);
	}
}

function moveThread($id, $forum) {
	if (!result("SELECT COUNT(*) FROM z_forums WHERE id = ?", [$forum])) return;

	$thread = fetch("SELECT forum, replies FROM z_threads WHERE id = ?", [$id]);
	query("UPDATE z_threads SET forum = ? WHERE id = ?", [$forum, $id]);

	$last1 = fetch("SELECT lastdate,lastuser,lastid FROM z_threads WHERE forum = ? ORDER BY lastdate DESC LIMIT 1", [$thread['forum']]);
	$last2 = fetch("SELECT lastdate,lastuser,lastid FROM z_threads WHERE forum = ? ORDER BY lastdate DESC LIMIT 1", [$forum]);
	if ($last1)
		query("UPDATE z_forums SET posts = posts - (? + 1), threads = threads - 1, lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
		[$thread['replies'], $last1['lastdate'], $last1['lastuser'], $last1['lastid'], $thread['forum']]);

	if ($last2)
		query("UPDATE z_forums SET posts = posts + (? + 1), threads = threads + 1, lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
		[$thread['replies'], $last2['lastdate'], $last2['lastuser'], $last2['lastid'], $forum]);
}

function getForumByThread($tid) {
	static $cache;
	return isset($cache[$tid]) ? $cache[$tid] : $cache[$tid] = result("SELECT forum FROM z_threads WHERE id = ?", [$tid]);
}
