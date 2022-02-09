<?php
require("lib/common.php");

$query = (isset($_GET['q']) ? $_GET['q'] : '');
$where = (isset($_GET['w']) ? $_GET['w'] : 0);
$forum = (isset($_GET['f']) ? $_GET['f'] : 0);

ob_start();

?>
<table class="c1">
	<tr class="h"><td class="b h"><?php echo __("Search")?></td>
	<tr><td class="b n1">
		<form action="search.php" method="get"><table>
			<tr>
				<td><?php echo __("Search for")?></td>
				<td><input type="text" name="q" size="40" value="<?=htmlspecialchars($query, ENT_QUOTES) ?>"></td>
			</tr><tr>
				<td></td>
				<td>
					in <input type="radio" class="radio" name="w" value="0" id="threadtitle" <?=(($where == 0) ? 'checked' : '') ?>><label for="threadtitle"><?php echo __("thread title")?></label>
					<input type="radio" class="radio" name="w" value="1" id="posttext" <?=(($where == 1) ? 'checked' : '') ?>><label for="posttext"><?php echo __("post text")?></label>
					<br><input type="submit" name="action" value="<?php echo __("Search")?>">
				</td>
			</tr>
		</table></form>
	</td></tr>
</table>
<?php
if (!isset($_GET['action']) || strlen($query) < 3) {
	if (isset($_GET['action']) && strlen($query) < 3) {
		echo '<br><table class="c1"><tr><td class="b n1 center">'.__("Please enter more than 2 characters!").'</td></tr></table>';
	}
	$content = ob_get_contents();
	ob_end_clean();

	$twig = _twigloader();
	echo $twig->render('forum/_legacy.twig', [
		'page_title' => __("Search"),
		'content' => $content
	]);

	die();
}

?><br>
<table class="c1"><tr class="h"><td class="b h" style="border-bottom:0"><?php echo __("Results")?></td></tr></table>
<?php
$squery = preg_replace("@[^\" a-zA-Z0-9]@", '', $query);
preg_match_all("@\"([^\"]+)\"@", $squery, $matches);
foreach ($matches[0] as $key => $value) {
	$squery = str_replace($value, " !$key ", $squery);
}
$squery = str_replace('"', '', $squery);
while (strpos($squery, "  ") != false) {
	$squery = str_replace("  ", " ", $squery);
}
$wordor = explode(" ", trim($squery));
$string = $nextbool = '';
$lastbool = 0;
$defbool = "AND";
if ($where == 1) {
	$searchfield = "pt.text";
} else {
	$searchfield = "t.title";
}
$boldify = [];
foreach ($wordor as $num => $word) {
	if ($lastbool == 0) {
		$nextbool = $defbool;
	}
	if ((($word == "OR") || ($word == "AND")) && !empty($string)) {
		$nextbool = $word;
		$lastbool = 1;
	} else {
		if (substr($word, 0, 1) == "!") {
			$string .= $nextbool." ".$searchfield." LIKE '%".$matches[1][substr($word, 1)]."%' ";
			$boldify[$num] = "@".$matches[1][substr($word, 1)]."@i";
		} else {
			$string .= $nextbool." ".$searchfield." LIKE '%".$word."%' ";
			$boldify[$num] = "@".$word."@i";
		}
	}
}
$string = trim(substr($string, strlen($defbool)));
if ($forum)
	$string .= " AND f.id='$forum' ";

if ($where == 1) {
	$fieldlist = userfields_post();
	$posts = query("SELECT ".userfields('u','u').", $fieldlist p.*, pt.text, pt.date ptdate, pt.user ptuser, pt.revision, t.id tid, t.title ttitle, t.forum tforum "
		."FROM z_posts p "
		."LEFT JOIN z_poststext pt ON p.id=pt.id "
		."LEFT JOIN z_poststext pt2 ON pt2.id=pt.id AND pt2.revision=(pt.revision+1) "
		."LEFT JOIN users u ON p.user=u.id "
		."LEFT JOIN z_threads t ON p.thread=t.id "
		."LEFT JOIN z_forums f ON f.id=t.forum "
		."WHERE $string AND ISNULL(pt2.id) "
		."AND f.id IN ".forumsWithViewPerm()
		."ORDER BY p.id");

	for ($i = 1; $post = $posts->fetch(); $i++) {
		$pthread['id'] = $post['tid'];
		$pthread['title'] = $post['ttitle'];
		$post['text'] = preg_replace($boldify,"<b>\\0</b>",$post['text']);
		echo '<br>' . threadpost($post,$pthread);
	}

	if ($i == 1) {
		ifEmptyQuery(__("No posts found."), 1, true);
	}
} else {
	$page = (isset($_GET['page']) ? $_GET['page'] : 1);
	if ($page < 1) $page = 1;
	$threads = query("SELECT ".userfields('u', 'u').", t.* "
		."FROM z_threads t "
		."LEFT JOIN users u ON u.id=t.user "
		."LEFT JOIN z_forums f ON f.id=t.forum "
		."WHERE $string AND f.id IN ".forumsWithViewPerm()
		."ORDER BY t.lastdate DESC "
		."LIMIT ".(($page-1)*$userdata['tpp']).",".$userdata['tpp']);
	$threadcount = result("SELECT COUNT(*) "
		."FROM z_threads t "
		."LEFT JOIN z_forums f ON f.id=t.forum "
		."WHERE $string AND f.id IN ".forumsWithViewPerm());
	?><table class="c1">
		<tr class="c">
			<td class="b h"><?php echo __("Title")?></td>
			<td class="b h" style="min-width:80px"><?php echo __("Started by")?></td>
			<td class="b h" width="200"><?php echo __("Date")?></td>
		</tr><?php

	for ($i = 1; $thread = $threads->fetch(); $i++) {
		if (!$thread['title']) $thread['title'] = '';

		$tr = ($i % 2 ? 'n2' :'n3');

		?><tr class="<?=$tr ?> center">
			<td class="b left wbreak">
				<a href="thread.php?id=<?=$thread['id'] ?>"><?=esc($thread['title']) ?></a> <?=($thread['sticky'] ? __(" (Sticky)") : '')?>
			</td>
			<td class="b"><?=userlink($thread,'u') ?></td>
			<td class="b"><?=date($dateformat,$thread['lastdate']) ?></td>
		</tr><?php
	}
	if ($i == 1) {
		ifEmptyQuery(__("No threads found."), 6);
	}

	$query = urlencode($query);
	$fpagelist = pagelist($threadcount, $userdata['tpp'], "search.php?q=$query&action=Search&w=0&f=$forum", $page);
	?></table><?php echo $fpagelist;
}

$content = ob_get_contents();
ob_end_clean();

$twig = _twigloader();
echo $twig->render('forum/_legacy.twig', [
	'page_title' => __("Search"),
	'content' => $content
]);
