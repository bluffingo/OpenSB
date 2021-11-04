<?php
require('lib/common.php');

//if (!hasPerm('edit-groups')) error('403', __("You have no permissions to do this!"));

$act = (isset($_GET['act']) ? $_GET['act'] : '');
$errmsg = '';
$caneditperms = hasPerm('edit-permissions');

if ($act == 'delete') {
	$id = $_GET['id'];
	$group = fetch("SELECT * FROM z_groups WHERE id = ?", [$id]);

	if (!$group)
		$errmsg = __("Cannot delete group: invalid group ID");
	else {
		$usercount = result("SELECT COUNT(*) FROM users WHERE group_id = ?", [$group['id']]);
		if ($usercount > 0) $errmsg = __("This group cannot be deleted because it contains users");

		if (!$errmsg && !$caneditperms) {
			$permcount = result("SELECT COUNT(*) FROM z_permx WHERE x_type = 'group' AND x_id = ?", [$group['id']]);
			if ($permcount > 0) $errmsg = __("This group cannot be deleted because it has permissions attached and you may not edit permissions.");
		}

		if (!$errmsg) {
			query("DELETE FROM z_groups WHERE id = ?", [$group['id']]);
			query("DELETE FROM z_permx WHERE x_type = 'group' AND x_id = ?", [$group['id']]);
			query("UPDATE z_groups SET inherit_group_id = 0 WHERE inherit_group_id = ?", [$group['id']]);
			redirect('editgroups.php');
		}
	}
} else if (isset($_POST['submit']) && ($act == 'new' || $act == 'edit')) {
	$title = trim($_POST['title']);

	$parentid = $_POST['inherit_group_id'];
	if ($parentid < 0 || $parentid > result("SELECT MAX(id) FROM z_groups")) $parentid = 0;

	if ($act == 'edit') {
		$recurcheck = [$_GET['id']];
		$pid = $parentid;
		while ($pid > 0) {
			if ($pid == $recurcheck[0]) {
				$errmsg = __("Endless recursion detected, choose another parent for this group");
				break;
			}

			$recurcheck[] = $pid;
			$pid = result("SELECT inherit_group_id FROM z_groups WHERE id = ?",[$pid]);
		}
	}

	if (!$errmsg) {
		$sortorder = (int)$_POST['sortorder'];

		$visible = $_POST['visible'] ? 1:0;

		if (empty($title))
			$errmsg = __("You must enter a name for the group.");
		else {
			$values = [$title, $_POST['nc'], $parentid, $sortorder, $visible];

			if ($act == 'new')
				query("INSERT INTO z_groups VALUES (0,?,?,?,?,?)", $values);
			else {
				$values[] = $_GET['id'];
				query("UPDATE z_groups SET title = ?,nc = ?,inherit_group_id = ?,sortorder = ?,visible = ? WHERE id = ?", $values);
			}
			redirect('editgroups.php');
		}
	}
}

ob_start();

if ($act == 'new' || $act == 'edit') {
	$pagebar = [
		'breadcrumb' => [['href'=>'./', 'title'=>__("Main")], ['href'=>'editgroups.php', 'title'=>__("Edit groups")]],
		'title' => '',
		'actions' => [['href'=>'editgroups.php?act=new', 'title'=>__("New group")]],
		'message' => $errmsg
	];

	if ($act == 'new') {
		$group = ['id'=>0, 'title'=>'', 'nc'=>'', 'inherit_group_id'=>0, 'sortorder'=>0, 'visible'=>0];
		$pagebar['title'] = 'New group';
	} else {
		$group = fetch("SELECT * FROM z_groups WHERE id = ?",[$_GET['id']]);
		if (!$group) error("404", "Invalid group ID.");
		$pagebar['title'] = 'Edit group';
	}

	if ($group) {
		$grouplist = [0 => '(none)'];
		$allgroups = query("SELECT id,title FROM z_groups WHERE id != ? ORDER BY sortorder",[$group['id']]);
		while ($g = $allgroups->fetch())
			$grouplist[$g['id']] = $g['title'];

		renderPageBar($pagebar);
		echo '<br><form method="post"><table class="c1">' .
			'<tr class="h"><td class="b h" colspan="2">'.__("Group Settings").'</td>'
.	fieldrow(__("Name"), fieldinput(50, 255, 'title', $group['title']))
.	fieldrow(__("Parent group"), fieldselect('inherit_group_id', $group['inherit_group_id'], $grouplist))
.	fieldrow(__("Sort order"), fieldinput(4, 8, 'sortorder', $group['sortorder']))
.	fieldrow(__("Visibility"), fieldoption('visible', $group['visible'], [__("Invisible"), __("Visible")]))
.	fieldrow(__("Color"), fieldinput(6,6,'nc',$group['nc']))
.	'<tr class="n1"><td class="b"></td><td class="b"><input type="submit" name="submit" value="'.__("Apply changes").'"></td></table></form><br>';
		$pagebar['message'] = '';
		renderPageBar($pagebar);
	}
} else {
	$pagebar = [
		'breadcrumb' => [['href'=>'./', 'title'=>__("Main")]],
		'title' => __("Edit groups"),
		'actions' => [['href'=>'editgroups.php?act=new', 'title'=>__("New group")]],
		'message' => $errmsg
	];

	renderPageBar($pagebar);
	echo '<br>';

	$header = [
		'sort' => ['name'=>__("Order"), 'width'=>'32px', 'align'=>'center'],
		'id' => ['name'=>'#', 'width'=>'32px', 'align'=>'center'],
		'name' => ['name'=>__("Name"), 'align'=>'center'],
		'parent' => ['name'=>__("Parent group"), 'width' => '240px', 'align'=>'center'],
		'actions' => ['name'=>'', 'width'=>'240px', 'align'=>'right'],
	];

	$groups = query("SELECT g.*, pg.title parenttitle FROM z_groups g LEFT JOIN z_groups pg ON pg.id=g.inherit_group_id ORDER BY sortorder");
	$data = [];

	while ($group = $groups->fetch()) {
		$name = esc($group['title']);
		if ($group['visible']) $name = "<strong>{$name}</strong>";
		if ($group['nc']) $name = str_replace('<strong>', "<strong style=\"color: #{$group['nc']};\">", $name);

		$actions = [];
		if ($caneditperms) $actions[] = ['href'=>'editperms.php?gid='.$group['id'], 'title'=>__("Edit perms")];
		$actions[] = ['href'=>'editgroups.php?act=edit&id='.$group['id'], 'title'=>__("Edit")];
		if ($caneditperms && $group['id'] > 7)
			$actions[] = ['href'=>'editgroups.php?act=delete&id='.$group['id'], 'title'=>__("Delete"),
				'confirm'=>__('Are you sure you want to delete the group "%s"?', [esc($group['title'])])];

		$data[] = [
			'sort' => $group['sortorder'],
			'id' => $group['id'],
			'name' => $name,
			'parent' => $group['parenttitle'] ? esc($group['parenttitle']) : '<small>(none)</small>',
			'actions' => renderActions($actions,true),
		];
	}

	renderTable($data, $header);
	echo '<br>';
	$pagebar['message'] = '';
	renderPageBar($pagebar);
}

$content = ob_get_contents();
ob_end_clean();

$twig = _twigloader();
echo $twig->render('forum/_legacy.twig', [
	'page_title' => __("Edit groups"),
	'content' => $content
]);
