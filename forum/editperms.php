<?php
require('lib/common.php');

$permlist = null;

if (!hasPerm('edit-permissions')) error('403', __("You have no permissions to do this!"));

if (isset($_GET['gid'])) {
	$id = (int)$_GET['gid'];
	if ((isRootGid($id) || (!canEditGroupAssets() && $id!=$userdata['group_id'])) && !hasPerm('no-restrictions')) {
		error('403', __("You have no permissions to do this!"));
	}
	if ($userdata['group_id'] == $id && !hasPerm('edit-own-permissions')) {
		error('403', __("You have no permissions to do this!"));
	}
	$permowner = fetch("SELECT id,title,inherit_group_id FROM z_groups WHERE id=?", [$id]);
	$type = 'group';
} else if (isset($_GET['uid'])) {
	$id = (int)$_GET['uid'];

	$tuser = result("SELECT group_id FROM users WHERE id = ?",[$id]);
	if ((isRootGid($tuser) || (!canEditUserAssets() && $id != $userdata['id'])) && !hasPerm('no-restrictions')) {
		error('403', __("You have no permissions to do this!"));
	}

	if ($id == $userdata['id'] && !hasPerm('edit-own-permissions')) {
		error('403', __("You have no permissions to do this!"));
	}
	$permowner = fetch("SELECT u.id,u.name title,u.group_id,g.title group_title FROM users u LEFT JOIN z_groups g ON g.id=u.group_id WHERE u.id=?", [$id]);
	$type = 'user';
} else if (isset($_GET['fid'])) {
	$id = (int)$_GET['fid'];
	$permowner = fetch("SELECT id,title FROM z_forums WHERE id=?", [$id]);
	$type = 'forum';
} else {
	$id = 0;
	$permowner = null;
	$type = '';
}

if (!$permowner) error("404", __("Invalid {$type} ID."));

$errmsg = '';

if (isset($_POST['addnew'])) {
	$revoke = (int)$_POST['revoke_new'];
	$permid = $_POST['permid_new'];
	$bindval = (int)$_POST['bindval_new'];

	if (hasPerm('no-restrictions') || $permid != 'no-restrictions') {
		query("INSERT INTO z_permx (x_id,x_type,perm_id,permbind_id,bindvalue,`revoke`) VALUES (?,?,?,'',?,?)",
			[$id, $type, $permid, $bindval, $revoke]);
		$msg = __("The %s permission has been successfully assigned!");
	} else {
		$msg = __("You do not have the permissions to assign the %s permission!");
	}
} else if (isset($_POST['apply'])) {
	$keys = array_keys($_POST['apply']);
	$pid = $keys[0];

	$revoke = (int)$_POST['revoke'][$pid];
	$permid = $_POST['permid'][$pid];
	$bindval = (int)$_POST['bindval'][$pid];

	if (hasPerm('no-restrictions') || $permid != 'no-restrictions') {
		query("UPDATE z_permx SET perm_id = ?, bindvalue = ?, `revoke` = ? WHERE id = ?",
			[$permid, $bindval, $revoke, $pid]);
		$msg = __("The %s permission has been successfully edited!");
	} else {
		$msg = __("You do not have the permissions to edit the %s permission!");
	}
} else if (isset($_POST['del'])) {
	$keys = array_keys($_POST['del']);
	$pid = $keys[0];
	$permid = $_POST['permid'][$pid];
	if (hasPerm('no-restrictions') || $permid != 'no-restrictions') {
		query("DELETE FROM z_permx WHERE id = ?", [$pid]);
		$msg = __("The %s permission has been successfully deleted!");
	} else {
		$msg = __("You do not have the permissions to delete the %s permission!");
	}
}

ob_start();

$pagebar = [
	'breadcrumb' => [['href'=>'./', 'title'=>__("Main")]],
	'title' => __("Edit permissions"),
	'actions' => [],
	'message' => (isset($msg) ? sprintf($msg, titleForPerm($permid)) : '')
];

renderPageBar($pagebar);

echo '<br><form action="" method="POST">';

$header = ['c0' => ['name' => '&nbsp;'], 'c1' => ['name' => '&nbsp;']];
$data = [];

$permset = PermSet($type, $id);
$row = []; $i = 0;
while ($perm = $permset->fetch()) {
	$pid = $perm['id'];

	$field = RevokeSelect("revoke[{$pid}]", $perm['revoke'])
			.PermSelect("permid[{$pid}]", $perm['perm_id'])
			.sprintf(
				__("for ID").' <input type="text" name="bindval[%s]" value="%s" size="3" maxlength="8">'
				.' <input type="submit" name="apply[%s]" value="'.__("Apply").'">'
				.' <input type="submit" name="del[%s]" value="'.__("Remove").'">',
			$pid, $perm['bindvalue'], $pid, $pid);
	$row['c'.$i] = $field;

	$i++;
	if ($i == 2) {
		$data[] = $row;
		$row = [];
		$i = 0;
	}
}
if (($i % 2) != 0) {
	$row['c1'] = '&nbsp;';
	$data[] = $row;
}

renderTable($data, $header);

$header = ['c0' => ['name' => __("Add permission")]];

$field = RevokeSelect("revoke_new", 0)
		.PermSelect("permid_new", null)
		.__("for ID").' <input type="text" name="bindval_new" value="" size=3 maxlength=8> <input type="submit" name="addnew" value="'.__("Add").'">';
$data = [['c0' => $field]];
renderTable($data, $header);

echo "</form><br>";

$permset = PermSet($type, $id);
$permsassigned = [];

$permoverview = '<strong>'.ucfirst($type).' permissions:</strong><br>'.PermTable($permset);

if ($type == 'group' && $permowner['inherit_group_id'] > 0) {
	$permoverview .= '<br><hr><strong>'.__("Permissions inherited from parent groups:").'</strong><br>';
	$parentid = $permowner['inherit_group_id'];
} else if ($type == 'user') {
	$permoverview .= '<hr><strong>'.__('Permissions inherited from the group %s.', [esc($permowner['group_title'])]).'":</strong><br>';
	$parentid = $permowner['group_id'];
}

while (isset($parentid) && $parentid > 0) {
	$parent = fetch("SELECT title,inherit_group_id FROM z_groups WHERE id=?", [$parentid]);
	$permoverview .= '<br>'.esc($parent['title']).':<br>' . PermTable(PermSet('group', $parentid));
	$parentid = $parent['inherit_group_id'];
}

$header = ['cell' => ['name'=>__("Permissions overview for {$type} '%s'", [esc($permowner['title'])])]];
$data = [['cell' => $permoverview]];
renderTable($data, $header);

echo '<br>';
$pagebar['message'] = '';
renderPageBar($pagebar);

$content = ob_get_contents();
ob_end_clean();

$twig = _twigloader();
echo $twig->render('forum/_legacy.twig', [
	'page_title' => __("Edit perms"),
	'content' => $content
]);

function PermSelect($name, $sel) {
	global $permlist;

	if (!$permlist) {
		$perms = query("SELECT p.id permid, p.title permtitle FROM z_perm p ORDER BY p.title ASC");

		$permlist = [];
		while ($perm = $perms->fetch()) $permlist[] = $perm;
	}

	$out = '<select name="'.$name.'">';
	$firstletter = '';
	foreach ($permlist as $perm) {
		if (substr($perm['permtitle'], 0, 1) !== $firstletter) {
			if (!empty($firstletter)) $out .= '</optgroup>';
			$firstletter = substr($perm['permtitle'], 0, 1);
			$out .= '<optgroup label="'.$firstletter.'">';
		}
		$chk = ($perm['permid'] == $sel) ? ' selected="selected"' : '';
		$out .= sprintf('<option value="%s"%s>%s</option>', esc($perm['permid']), $chk, esc($perm['permtitle']));
	}
	$out .= '</select>';

	return $out;
}

function RevokeSelect($name, $sel) {
	$out = sprintf('<select name="%s"><option value="0"%s>'.__("Grant").'</option><option value="1"%s>'.__("Revoke").'</option></select> ',
		$name, ($sel == 0 ? ' selected="selected"' : ''), ($sel == 1 ? ' selected="selected"' : ''));
	return $out;
}

function PermSet($type, $id) {
	return query("SELECT x.*, p.title permtitle FROM z_permx x LEFT JOIN z_perm p ON p.id=x.perm_id WHERE x.x_type=? AND x.x_id=?", [$type,$id]);
}

function PermTable($permset) {
	global $permsassigned;
	$ret = '';

	$i = 0;
	while ($perm = $permset->fetch()) {
		$key = $perm['perm_id'];
		if ($perm['bindvalue']) $key .= '['.$perm['bindvalue'].']';

		$discarded = false;
		if (isset($permsassigned[$key])) $discarded = true;
		else $permsassigned[$key] = true;

		$permtitle = $perm['permtitle'];
		if (!$permtitle) $permtitle = $perm['perm_id'];

		$ret .= '<td style="width:25%">&bull; ';
		if ($discarded) $ret .= '<s>';
		if ($perm['revoke']) $ret .= '<span style="color:#f88;">'.__("Revoke").'</span>: ';
		else $ret .= '<span style="color:#8f8;">'.__("Grant").'</span>: ';
		$ret .= "'".esc($permtitle)."'";

		if ($perm['bindvalue']) {
			$bindtitle = strtolower($perm['permbind_id']);
			if (!$bindtitle) $bindtitle = $perm['permbind_id'];
			if (!$bindtitle) $bindtitle = 'ID';
			$ret .= ' for '.esc($bindtitle).' #'.$perm['bindvalue'];
		}

		if ($discarded) $ret .= '</s>';

		$ret .= '</td>';

		$i++;
		if (($i % 4) == 0) $ret .= '</tr><tr>';
	}

	if (($i % 4) != 0)
		$ret .= '<td colspan="'.(4-($i%4)).'">&nbsp;</td>';

	if (!$ret) $ret = '<td>&bull; None</td>';

	return '<table class="fullwidth"><tr>'.$ret.'</tr></table>';
}