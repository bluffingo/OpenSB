<?php

/**
 * Renders a table in HTML using $headers for column definition and $data to fill cells with data.
 *
 * @param array $headers An associative array of column definitions:
 *	key				column key
 *	value['name']	Display text for the column header
 *	value['width']	Specify a fixed width size (CSS width:)
 *	value['align']	Align the contents in the column
 *
 * @param array $data An associative array of cell data values:
 *	key				column key (must match the header column key)
 *	value			cell value
 */
function renderTable($data, $headers) {
	$zebra = 1;

	echo '<table class="c1"><tr class="h">';
	foreach ($headers as $headerID => $headerCell) {
		$width = (isset($headerCell['width']) ? ' style="width:'.$headerCell['width'].'"' : '');
		echo "<td class=\"b h\" $width>".$headerCell['name']."</td>";
	}
	echo "</tr>";
	foreach ($data as $dataCell) {
		echo "<tr>";
		foreach ($dataCell as $id => $value) {
			$align = (isset($headers[$id]['align']) ? $headers[$id]['align'] : '');
			echo "<td class=\"b n$zebra $align\">$value</td>";
		}
		echo "</tr>";
		$zebra = ($zebra == 1 ? 2 : 1);
	}
	echo "</table>";
}

function newStatus($type) {
	$text = match ($type) {
		'n'  => 'NEW',
		'o'  => 'OFF',
		'on' => 'OFF'
	};
	$statusimg = match ($type) {
		'n'  => 'new.png',
		'o'  => 'off.png',
		'on' => 'offnew.png'
	};

	return "<img src=\"assets/status/$statusimg\" alt=\"$text\">";
}

function renderActions($actions, $ret = false) {
	$out = '';
	$i = 0;
	foreach ($actions as $action) {
		if (isset($action['confirm'])) {
			if ($action['confirm'] === true)
				$confirmmsg = __('Are you sure you want to %s?', [$action['title']]);
			else
				$confirmmsg = str_replace("'", "\\'", $action['confirm']);

			$href = sprintf(
				"javascript:if(confirm('%s')) window.location.href='%s'; else void('');",
			$confirmmsg, $action['href']);
		} else {
			$href = (isset($action['href']) ? $action['href'] : '');
		}
		if ($i++)
			$out .= ' | ';
		if (isset($action['href'])) {
			$out .= sprintf('<a href="%s">%s</a>', htmlentities($href, ENT_QUOTES), $action['title']);
		} else {
			$out .= $action['title'];
		}
	}
	if ($ret)
		return $out;
	else
		echo $out;
}

function renderPageBar($pagebar) {
	if (empty($pagebar)) return;

	echo "<table width=100%><td class=nb>";
	if (!empty($pagebar['breadcrumb'])) {
		foreach ($pagebar['breadcrumb'] as $action)
			printf('<a href=%s>%s</a> &raquo; ', '"'.htmlentities($action['href'], ENT_QUOTES).'"', $action['title']);
	}
	echo $pagebar['title'].'</td><td class="nb right">';
	if (!empty($pagebar['actions']))
		renderActions($pagebar['actions']);
	else
		echo "&nbsp;";
	echo "</td></table>";
	if (!empty($pagebar['message'])) {
		echo '<table width=100% class=c1><tr><td class="center">'.$pagebar['message'].'</td></tr></table><br>';
	}
}

function fieldrow($title, $input) {
	return sprintf('<tr><td class="b n1 center">%s:</td><td class="b n2">%s</td>', $title, $input);
}

function fieldinput($size, $max, $field, $value = null) {
	global $user;
	$val = str_replace('"', '&quot;', (isset($value) ? $value : $user[$field]));
	return sprintf('<input type="text" name="%s" size="%s" maxlength="%s" value="%s">', $field, $size, $max, $val);
}

function fieldoption($field, $checked, $choices) {
	$text = '';
	foreach ($choices as $k => $v)
		$text .= sprintf('<label><input type="radio" name="%s" value="%s" %s>%s </label>', $field, $k, ($k == $checked ? ' checked' : ''), $v);
	return $text;
}

function fieldselect($field, $checked, $choices) {
	$text = sprintf('<select name="%s">', $field);
	foreach ($choices as $k => $v)
		$text .= sprintf('<option value="%s"%s>%s</option>', $k, ($k == $checked ? ' selected' : ''), $v);
	$text .= '</select>';
	return $text;
}

function pagelist($total, $limit, $url, $sel = 0, $showall = false) {
	$pagelist = '';
	$pages = ceil($total / $limit);
	if ($pages < 2) return '';
	for ($i = 1; $i <= $pages; $i++) {
		if (	$showall	// If we don't show all the pages, show:
			|| ($i < 7 || $i > $pages - 7)		// First / last 7 pages
			|| ($i > $sel - 5 && $i < $sel + 5)	// 10 choices around the selected page
			|| !($i % 10)						// Show 10, 20, etc...
		) {
			if ($i == $sel)
				$pagelist .= " $i";
			else
				$pagelist .= " <a href=\"$url&page=$i\">$i</a>";
		} else if (substr($pagelist, -1) != '.') {
			$pagelist .= ' ...';
		}
	}

	$listhtml = '<div class="pagelist">Pages: %s</div>';

	return sprintf($listhtml, $pagelist);
}

function forumlist($currentforum = -1) {
	$r = query("SELECT c.title ctitle,f.id,f.title,f.cat,f.private FROM z_forums f LEFT JOIN z_categories c ON c.id=f.cat ORDER BY c.ord,c.id,f.ord,f.id");
	$out = '<select id="forumselect">';
	$c = -1;
	while ($d = $r->fetch()) {
		if (!canViewForum($d))
			continue;

		if ($d['cat'] != $c) {
			if ($c != -1)
				$out .= '</optgroup>';
			$c = $d['cat'];
			$out .= '<optgroup label="'.$d['ctitle'].'">';
		}
		$out .= sprintf(
			'<option value="%s"%s>%s</option>',
		$d['id'], ($d['id'] == $currentforum ? ' selected="selected"' : ''), $d['title']);
	}
	$out .= "</optgroup></select>";

	return $out;
}

function ifEmptyQuery($message, $colspan = 0, $table = false) {
	if ($table) echo '<table class="c1">';
	echo '<tr><td class="b n1 center" '.($colspan != 0 ? "colspan=$colspan" : '')."><p>$message</p></td></tr>";
	if ($table) echo '</table>';
}

function _twigloader($subfolder = '') {
	global $dateformat, $frontend;
	chdir('../');
	$twig = twigloader($subfolder, function () use ($subfolder, $frontend) {
		return new \Twig\Loader\FilesystemLoader('templates/' . $frontend . $subfolder);
	}, function ($loader, $doCache) {

		return new \Twig\Environment($loader, [
			'cache' => ($doCache ? "../".$doCache : $doCache),
		]);
	});
	
	$twig->addExtension(new PrincipiaForumExtension());

	$twig->addGlobal('forum_dateformat', $dateformat);

	return $twig;
}