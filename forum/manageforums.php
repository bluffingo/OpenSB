<?php
require('lib/common.php');

if (!hasPerm('edit-forums')) error('403', __("You have no permissions to do this!"));

$error = '';

if (isset($_POST['savecat'])) {
	// save new/existing category
	$cid = $_GET['cid'];
	$title = $_POST['title'];
	$ord = (int)$_POST['ord'];
	if (!trim($title))
		$error = __("Please enter a title for the category.");
	else {
		if ($cid == 'new') {
			$cid = result("SELECT MAX(id) FROM z_categories");
			if (!$cid) $cid = 0;
			$cid++;
			query("INSERT INTO z_categories (id,title,ord) VALUES (?,?,?)", [$cid, $title, $ord]);
		} else {
			$cid = (int)$cid;
			if (!result("SELECT COUNT(*) FROM z_categories WHERE id=?",[$cid])) redirect('manageforums.php');
			query("UPDATE z_categories SET title = ?, ord = ? WHERE id = ?", [$title, $ord, $cid]);
		}
		redirect('manageforums.php?cid='.$cid);
	}
} else if (isset($_POST['delcat'])) {
	// delete category
	$cid = (int)$_GET['cid'];
	query("DELETE FROM z_categories WHERE id = ?",[$cid]);

	redirect('manageforums.php');
} else if (isset($_POST['saveforum'])) {
	// save new/existing forum
	$fid = $_GET['fid'];
	$cat = (int)$_POST['cat'];
	$title = $_POST['title'];
	$descr = $_POST['descr'];
	$ord = (int)$_POST['ord'];
	$private = isset($_POST['private']) ? 1 : 0;
	$readonly = isset($_POST['readonly']) ? 1 : 0;

	if (!trim($title))
		$error = __("Please enter a title for the forum.");
	else {
		if ($fid == 'new') {
			$fid = result("SELECT MAX(id) FROM z_forums");
			if (!$fid) $fid = 0;
			$fid++;
			query("INSERT INTO z_forums (id,cat,title,descr,ord,private,readonly) VALUES (?,?,?,?,?,?,?)",
				[$fid, $cat, $title, $descr, $ord, $private, $readonly]);
		} else {
			$fid = (int)$fid;
			if (!result("SELECT COUNT(*) FROM z_forums WHERE id=?",[$fid]))
				redirect('manageforums.php');
			query("UPDATE z_forums SET cat=?, title=?, descr=?, ord=?, private=?, readonly=? WHERE id=?",
				[$cat, $title, $descr, $ord, $private, $readonly, $fid]);
		}
		saveperms('forums', $fid);
		redirect('manageforums.php?fid='.$fid);
	}
} else if (isset($_POST['delforum'])) {
	// delete forum
	$fid = (int)$_GET['fid'];
	query("DELETE FROM z_forums WHERE id=?",[$fid]);
	deleteperms('forums', $fid);
	redirect('manageforums.php');
}

if ($error) error("Error", $error);

ob_start();

echo '<style type="text/css">label { white-space: nowrap; } input:disabled { opacity: 0.5; }</style>';

if (isset($_GET['cid']) && $cid = $_GET['cid']) {
	// category editor
	if ($cid == 'new') {
		$cat = ['id' => 0, 'title' => '', 'ord' => 0];
	} else {
		$cid = (int)$cid;
		$cat = fetch("SELECT * FROM z_categories WHERE id=?",[$cid]);
	}
	?><form action="" method="POST">
		<table class="c1">
			<tr class="h"><td class="b h" colspan="2"><?=($cid == 'new' ? __("Create") : __("Edit")) ?> <?php echo __("category")?></td></tr>
			<tr>
				<td class="b n1 center"><?php __("Title:")?></td>
				<td class="b n2"><input type="text" name="title" value="<?=esc($cat['title']) ?>" size="50" maxlength="500"></td>
			</tr><tr>
				<td class="b n1 center"><?php echo __("Display order:")?></td>
				<td class="b n2"><input type="text" name="ord" value="<?=$cat['ord'] ?>" size="4" maxlength="10"></td>
			</tr>
			<tr class="h"><td class="b h" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="b n1 center"></td>
				<td class="b n2">
					<input type="submit" name="savecat" value="<?php echo __("Save category")?>">
						<?=($cid == 'new' ? '' : '<input type="submit" name="delcat" value="'.__("Delete category").'" onclick="if (!confirm("'.__("Really delete this category?").'")) return false;"> ') ?>
					<button type="button" id="back" onclick="window.location='manageforums.php';"><?php echo __("Back")?></button>
				</td>
			</tr>
		</table>
	</form><?php
} else if (isset($_GET['fid']) && $fid = $_GET['fid']) {
	// forum editor
	if ($fid == 'new') {
		$forum = ['id' => 0, 'cat' => 1, 'title' => '', 'descr' => '', 'ord' => 0, 'private' => 0, 'readonly' => 0];
	} else {
		$fid = (int)$fid;
		$forum = fetch("SELECT * FROM z_forums WHERE id=?",[$fid]);
	}
	$qcats = query("SELECT id,title FROM z_categories ORDER BY ord, id");
	$cats = [];
	while ($cat = $qcats->fetch())
		$cats[$cat['id']] = $cat['title'];
	$catlist = fieldselect('cat', $forum['cat'], $cats);

	?><form action="" method="POST">
		<table class="c1">
			<tr class="h"><td class="b h" colspan="2"><?=($fid == 'new' ? __("Create") : __("Edit")) ?> <?php __("forum")?></td></tr>
			<tr>
				<td class="b n1 center"><?php echo __("Title:")?></td>
				<td class="b n2"><input type="text" name="title" value="<?=esc($forum['title']) ?>" size="50" maxlength="500"></td>
			</tr><tr>
				<td class="b n1 center"><?php echo __("Description:")?><br><small><?php echo __("HTML allowed.")?></small></td>
				<td class="b n2"><textarea wrap="virtual" name="descr" rows="3" cols="50"><?=esc($forum['descr']) ?></textarea></td>
			</tr><tr>
				<td class="b n1 center"><?php echo __("Category:")?></td>
				<td class="b n2"><?=$catlist ?></td>
			</tr><tr>
				<td class="b n1 center"><?php echo __("Display order:")?></td>
				<td class="b n2"><input type="text" name="ord" value="<?=$forum['ord'] ?>" size="4" maxlength="10"></td>
			</tr><tr>
				<td class="b n1 center"></td>
				<td class="b n2">
					<label><input type="checkbox" name="private" value="1" <?=($forum['private'] ? ' checked':'') ?>> <?php echo __("Private forum")?></label>
					<label><input type="checkbox" name="readonly" value="1" <?=($forum['readonly'] ? ' checked' : '')?>> <?php echo __("Read-only")?></label>
				</td>
			</tr>
			<tr class="h"><td class="b h" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="b n1 center"></td>
				<td class="b n2">
					<input type="submit" name="saveforum" <?php echo 'value="'.__("Save forum")?>">
					<?=($fid == 'new' ? '' : '<input type="submit" name="delforum" value="'.__("Delete forum").'" onclick="if (!confirm("'.__("Really delete this forum?").'")) return false;">') ?>
					<button type="button" id="back" onclick="window.location='manageforums.php'"><?php echo __("Back")?></button>
				</td>
			</tr>
		</table><br>
		<?php permtable('forums', $fid) ?>
	</form><?php
} else {
	// main page -- category/forum listing

	$qcats = query("SELECT id,title FROM z_categories ORDER BY ord, id");
	$cats = [];
	while ($cat = $qcats->fetch())
		$cats[$cat['id']] = $cat;

	$qforums = query("SELECT f.id,f.title,f.cat FROM z_forums f LEFT JOIN z_categories c ON c.id=f.cat ORDER BY c.ord, c.id, f.ord, f.id");
	$forums = [];
	while ($forum = $qforums->fetch())
		$forums[$forum['id']] = $forum;

	$catlist = ''; $c = 1;
	foreach ($cats as $cat) {
		$catlist .= sprintf('<tr><td class="b n%s"><a href="manageforums.php?cid=%s">%s</a></td></tr>', $c, $cat['id'], $cat['title']);
		$c = ($c == 1) ? 2 : 1;
	}

	$forumlist = ''; $c = 1; $lc = -1;
	foreach ($forums as $forum) {
		if ($forum['cat'] != $lc) {
			$lc = $forum['cat'];
			$forumlist .= sprintf('<tr class="c"><td class="b c">%s</td></tr>', $cats[$forum['cat']]['title']);
		}
		$forumlist .= sprintf('<tr><td class="b n%s"><a href="manageforums.php?fid=%s">%s</a></td></tr>', $c, $forum['id'], $forum['title']);
		$c = ($c == 1) ? 2 : 1;
	}

	?><table class="fullwidth">
		<tr>
			<td class="nb" style="width:50%; vertical-align:top;">
				<table class="c1">
					<tr class="h"><td class="b"><?php echo __("Categories")?></td></tr>
					<?=$catlist ?>
					<tr class="h"><td class="b">&nbsp;</td></tr>
					<tr><td class="b n1"><a href="manageforums.php?cid=new"><?php echo __("New category")?></a></td></tr>
				</table>
			</td>
			<td class="nb" style="width:50%; vertical-align:top;">
				<table class="c1">
					<tr class="h"><td class="b"><?php echo __("Forums")?></td></tr>
					<?=$forumlist ?>
					<tr class="h"><td class="b">&nbsp;</td></tr>
					<tr><td class="b n1"><a href="manageforums.php?fid=new"><?php echo __("New forum")?></a></td></tr>
				</table>
			</td>
		</tr>
	</table><?php
}

$content = ob_get_contents();
ob_end_clean();

$twig = _twigloader();
echo $twig->render('forum/_legacy.twig', [
	'page_title' => __("Forum management"),
	'content' => $content
]);


function rec_grouplist($parent, $level, $tgroups, $groups) {
	foreach ($tgroups as $g) {
		if ($g['inherit_group_id'] != $parent)
			continue;

		$g['indent'] = $level;
		$groups[] = $g;

		$groups = rec_grouplist($g['id'], $level+1, $tgroups, $groups);
	}
	return $groups;
}
function grouplist() {
	global $usergroups;

	$groups = [];
	$groups = rec_grouplist(0, 0, $usergroups, $groups);

	return $groups;
}
function permtable($bind, $id) {
	global $rootgroup;
	
	$qperms = query("SELECT id,title FROM z_perm WHERE permbind_id=?",[$bind]);
	$perms = [];
	while ($perm = $qperms->fetch())
		$perms[$perm['id']] = $perm['title'];

	$groups = grouplist();

	$qpermdata = query("SELECT x.x_id,x.perm_id,x.revoke FROM z_permx x LEFT JOIN z_perm p ON p.id=x.perm_id WHERE x.x_type=? AND p.permbind_id=? AND x.bindvalue=?",
		['group',$bind,$id]);
	$permdata = [];
	while ($perm = $qpermdata->fetch())
		$permdata[$perm['x_id']][$perm['perm_id']] = !$perm['revoke'];

	echo '<table class="c1"><tr class="h"><td class="b">Group</td><td class="b" colspan="2">'.__("Permissions").'</td></tr>';

	$c = 1;
	foreach ($groups as $group) {
		if ($group['id'] == $rootgroup) break;

		$gid = $group['id'];
		$gtitle = esc($group['title']);

		$pf = $group['visible'] ? '<strong' : '<span';
		if ($group['nc']) $pf .= ' style="color:#'.esc($group['nc']).'"';
		$pf .= '>';
		$sf = $group['visible'] ? '</strong>' : '</span>';
		$gtitle = "{$pf}{$gtitle}{$sf}";

		$doinherit = false;
		$inherit = '';
		if ($group['inherit_group_id']) {
			$doinherit = !isset($permdata[$gid]) || empty($permdata[$gid]);

			$check = $doinherit ? ' checked="checked"' : '';
			$inherit = sprintf(
				'<label><input type="checkbox" name="inherit[%s]" value="1" onclick="toggleAll(\'perm_%s\',!this.checked);"%s> '.__("Inherit from parent").'</label>&nbsp;',
			$gid, $gid, $check);
		}

		$permlist = '';
		foreach ($perms as $pid => $ptitle) {
			$check = ($doinherit ? ' disabled="disabled"' : ($permdata[$gid][$pid] ? ' checked="checked"' : ''));

			$permlist .= sprintf(
				'<label><input type="checkbox" name="perm[%s][%s]" value="1" class="perm_%s"%s> %s</label> ',
			$gid, $pid, $gid, $check, $ptitle);
		}

		?><tr class="n<?=$c ?>">
			<td class="b" style="width:200px;"><span style="white-space:nowrap;"><?=str_repeat('&nbsp; &nbsp; ', $group['indent']) . $gtitle ?></span></td>
			<td class="b" style="width:100px;"><?=$inherit ?></td>
			<td class="b"><?=$permlist ?></td>
		</tr><?php

		$c = ($c == 1) ? 2 : 1;
	}

	?><tr class="n<?=$c ?>">
		<td class="b"></td>
		<td class="b" colspan="2">
			<input type="submit" name="saveforum" <?php echo 'value="'.__("Save forum")?>">
		</td>
	</tr></table><?php
}

function deleteperms($bind, $id) {
	query("DELETE x FROM z_permx x LEFT JOIN z_perm p ON p.id=x.perm_id WHERE x.x_type=? AND p.permbind_id=? AND x.bindvalue=?",
		['group', $bind, $id]);
}

function saveperms($bind, $id) {
	global $usergroups;

	$qperms = query("SELECT id FROM z_perm WHERE permbind_id=?",[$bind]);
	$perms = [];
	while ($perm = $qperms->fetch())
		$perms[] = $perm['id'];

	// delete the old perms
	deleteperms($bind, $id);

	// apply the new perms
	foreach ($usergroups as $gid => $group) {
		echo $gid;
		if (isRootGid($gid)) continue;
		
		
		if ($_POST['inherit'][$gid])
			continue;

		$myperms = $_POST['perm'][$gid];
		foreach ($perms as $perm)
			query("INSERT INTO `z_permx` (`x_id`,`x_type`,`perm_id`,`permbind_id`,`bindvalue`,`revoke`)
				VALUES (?,?,?,?,?,?)", [$gid, 'group', $perm, $bind, $id, $myperms[$perm]?0:1]);
	}
}