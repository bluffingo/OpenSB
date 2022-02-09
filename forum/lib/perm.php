<?php

// Mark what group is the root, default and banned group.
$bannedgroup = 1;
$defaultgroup = 3;
$rootgroup = 7;

// preload group data, makes things a lot easier afterwards
$usergroups = [];
function preloadGroupData() {
	global $usergroups;

	$r = query("SELECT * FROM z_groups");
	while ($g = $r->fetch())
		$usergroups[$g['id']] = $g;
}

//this processes the permission stack, in this order:
//-user permissions
//-user's primary group permissions, then the parent group's permissions, recursively until it reaches the top
//first encountered occurence of a permission has precendence (+/-)
function loadUserPermset() {
	global $logpermset, $userdata;

	//load user specific permissions
	$logpermset = permsForX('user',$userdata['id']);
	$logpermset = applyGroupPermissions($logpermset,$userdata['group_id']);
}

//Badge permset

function permsetForUser($userid) {
	$permset = [];
	//load user specific permissions
	$permset = permsForX('user',$userid);

	$permset = applyGroupPermissions($permset,gidForUser($userid));
	return $permset;
}

function isRootGid($gid) {
	global $rootgroup;
	if ($gid == $rootgroup)
		return true;
	else
		return false;
}

function gidForUser($userid) {
	$row = fetch("SELECT group_id FROM users WHERE id=?",[$userid]);
	return $row['group_id'];
}

function loadGuestPermset() {
	global $logpermset;
	$logpermset = [];
	$loggroups = [];
	foreach ($loggroups as $gid) {
		$logpermset = applyGroupPermissions($logpermset,$gid);
	}
}

function loadBotPermset() {
	global $logpermset;
	$logpermset = [];
	$loggroups = [];
	foreach ($loggroups as $gid) {
		$logpermset = applyGroupPermissions($logpermset,$gid);
	}
}

function titleForPerm($permid) {
	$row = fetch("SELECT title FROM z_perm WHERE id=?",[$permid]);
	return $row['title'];
}

function applyGroupPermissions($permset,$gid) {
	//apply group permissions from lowest node upwards
	while ($gid > 0) {
		$gpermset = permsForX('group',$gid);
		foreach ($gpermset as $k => $v) {
			//remove already added permissions
			if (inPermset($permset,$v)) unset($gpermset[$k]);
		}
		//merge permissions
		$permset = array_merge($permset,$gpermset);
		$gid = parentGroupForGroup($gid);
	}
	return $permset;
}

function inPermset($permset,$perm) {
	foreach ($permset as $v) {
		if (($v['id'] == $perm['id']) && ($v['bindvalue'] == $perm['bindvalue']))
			return true;
	}
	return false;
}

function canEditPost($post) {
	global $userdata;
	if (isset($post['user']) && $post['user'] == $userdata['id'] && hasPerm('update-own-post')) return true;
	else if (hasPerm('update-post')) return true;
	else if (isset($post['tforum']) && canCreateForumPosts($post['tforum'])) return true;
	return false;
}

function canEditGroupAssets() {
	if (hasPerm('edit-all-group')) return true;
	return false;
}

function canEditUserAssets() {
	if (hasPerm('edit-all-group-member')) return true;
	return false;
}

function canEditUser($uid) {
	global $userdata;

	$gid = gidForUser($uid);
	if (isRootGid($gid) && !hasPerm('no-restrictions')) return false;
	if ((!canEditUserAssets() && $uid!=$userdata['id']) && !hasPerm('no-restrictions')) return false;

	if ($uid == $userdata['id'] && hasPerm('update-own-profile')) return true;
	else if (hasPerm('update-profiles')) return true;
	return false;
}

function forumsWithViewPerm() {
	static $cache = '';
	if ($cache != '') return $cache;
	$cache = "(";
	$r = query("SELECT f.id, f.private, f.cat FROM z_forums f");
	while ($d = $r->fetch()) {
		if (canViewForum($d)) $cache .= $d['id'].',';
	}
	$cache .= "NULL)";
	return $cache;
}

function canViewForum($forum) {
	//must fulfill the following criteria

	//if the forum is private
	if ($forum['private']) {
		//and can view the forum
		if (!hasPerm('view-all-private-forums') && !hasPermWithBindvalue('view-private-forum',$forum['id'])) return false;
	}
	return true;
}

function needsLogin() {
	global $log;
	if (!$log) {
		error('403', "This page requires login.");
	}
}

function canCreateForumThread($forum) {
	global $log;
	if ($forum['readonly'] && !hasPerm('override-readonly-forums')) return false;

	//must fulfill the following criteria

	//can create public threads
	if (!hasPerm('create-public-thread')) return false;
	if (!$log) return false;

	//and if the forum is private
	if (isset($forum['private']) && $forum['private']) {
		//can view the forum
		if (!hasPerm('create-all-private-forum-threads') && !hasPermWithBindvalue('create-private-forum-thread',$forum['id'])) return false;
	}
	return true;
}

function canCreateForumPost($forum) {
	global $log;
	if ($forum['readonly'] && !hasPerm('override-readonly-forums')) return false;

	//must fulfill the following criteria

	//can create public threads
	if (!hasPerm('create-public-post')) return false;
	if (!$log) return false;

	//and if the forum is private
	if ($forum['private']) {
		//can view the forum
		if (!hasPerm('create-all-private-forum-posts') && !hasPermWithBindvalue('create-private-forum-post',$forum['id'])) return false;
	}
	return true;
}

function canCreateForumPosts($forumid) {
	if (!hasPerm('update-post') && !hasPermWithBindvalue('edit-forum-post',$forumid)) return false;
	return true;
}

function canDeleteForumPosts($forumid) {
	if (!hasPerm('delete-post') && !hasPermWithBindvalue('delete-forum-post',$forumid)) return false;
	return true;
}

function canEditForumThreads($forumid) {
	if (!hasPerm('update-thread') && !hasPermWithBindvalue('edit-forum-thread',$forumid)) return false;
	return true;
}

function hasPerm($permid) {
	global $logpermset;
	foreach ($logpermset as $k => $v) {
		if ($v['id'] == 'no-restrictions') return true;
		if ($permid == $v['id'] && !$v['revoke']) return true;
	}
	return false;
}

function hasPermWithBindvalue($permid,$bindvalue) {
	global $logpermset;
	foreach ($logpermset as $k => $v) {
		if ($v['id'] == 'no-restrictions') return true;
		if ($permid == $v['id'] && !$v['revoke'] && $bindvalue == $v['bindvalue'])
		return true;
	}
	return false;
}

function parentGroupForGroup($groupid) {
	global $usergroups;

	$gid = $usergroups[$groupid]['inherit_group_id'];
	if ($gid > 0) {
		return $gid;
	} else {
		return 0;
	}
}

function permsForX($xtype,$xid) {
	$res = query("SELECT * FROM z_permx WHERE x_type=? AND x_id=?", [$xtype,$xid]);

	$out = [];
	$c = 0;
	while ($row = $res->fetch()) {
		$out[$c++] = [
			'id' => $row['perm_id'],
			'bind_id' => $row['permbind_id'],
			'bindvalue' => $row['bindvalue'],
			'revoke' => $row['revoke'],
			'xtype' => $xtype,
			'xid' => $xid
		];
	}
	return $out;
}
