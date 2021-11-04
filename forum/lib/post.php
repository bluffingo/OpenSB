<?php

function postfilter($msg) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	$msg = $markdown->text($msg);

	$msg = preg_replace("'\[reply=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=showprivate.php?id=\\2>Sent by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/reply]', '<hr></blockquote>', $msg);
	$msg = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=thread.php?pid=\\2#\\2>Posted by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/quote]', '<hr></blockquote>', $msg);

	return $msg;
}

function esc($text) {
	return htmlspecialchars($text);
}

function threadpost($post, $pthread = '') {
	global $log, $dateformat, $userdata;

	if (isset($post['deleted']) && $post['deleted']) {
		if (canCreateForumPosts(getForumByThread($post['thread']))) {
			$postlinks = sprintf(
				'<a href="thread.php?pid=%s&pin=%s&rev=%s#%s">Peek</a> &bull; <a href="editpost.php?pid=%s&act=undelete">Undelete</a> &bull; ID: %s',
			$post['id'], $post['id'], $post['revision'], $post['id'], $post['id'], $post['id']);
		} else {
			$postlinks = 'ID: '.$post['id'];
		}

		$ulink = userlink($post, 'u');
		$text = <<<HTML
<table class="c1"><tr>
	<td class="b n1" style="border-right:0;width:180px">$ulink</td>
	<td class="b n1" style="border-left:0">
		<table width="100%">
			<td class="nb">(post deleted)</td>
			<td class="nb right">$postlinks</td>
		</table>
	</td>
</tr></table>
HTML;
		return $text;
	}

	$postheaderrow = $threadlink = $postlinks = $revisionstr = '';

	$post['utitle'] = $post['utitle'] . ((strlen($post['utitle']) >= 1) ? '<br>' : '');

	$post['id'] = (isset($post['id']) ? $post['id'] : 0);

	if ($pthread)
		$threadlink = sprintf(', in <a href="thread.php?id=%s">%s</a>', $pthread['id'], esc($pthread['title']));

	if (isset($post['id']) && $post['id'])
		$postlinks = "<a href=\"thread.php?pid=$post[id]#$post[id]\">".__("Link")."</a>";

	if (isset($post['revision']) && $post['revision'] >= 2)
		$revisionstr = " (".__("rev.")." {$post['revision']} ".__("of")." " . date($dateformat, $post['ptdate']) . " ".__("by")." " . userlink_by_id($post['ptuser']) . ")";

	if (isset($post['thread']) && $post['id'] && $userdata['id'] != 0)
		$postlinks .= " &bull; <a href=\"newreply.php?id=$post[thread]&pid=$post[id]\">".__("Reply")."</a>";

	// "Edit" link for admins or post owners, but not banned users
	if (isset($post['thread']) && canEditPost($post) && $post['id'])
		$postlinks .= " &bull; <a href=\"editpost.php?pid=$post[id]\">".__("Edit")."</a>";

	if (isset($post['thread']) && isset($post['id']) && canDeleteForumPosts(getForumByThread($post['thread'])))
		$postlinks .= ' &bull; <a href=\"editpost.php?pid='.$post['id'].'&act=delete\">'.__("Delete").'</a>';

	if (isset($post['thread']) && $post['id'])
		$postlinks .= " &bull; ".__("ID:")." $post[id]";

	if (isset($post['maxrevision']) && isset($post['thread']) && hasPerm('view-post-history') && $post['maxrevision'] > 1) {
		$revisionstr .= " &bull; ".__("Revision")." ";
		for ($i = 1; $i <= $post['maxrevision']; $i++)
			$revisionstr .= "<a href=\"thread.php?pid=$post[id]&pin=$post[id]&rev=$i#$post[id]\">$i</a> ";
	}

	$ulink = userlink($post, 'u');
	$pdate = date($dateformat, $post['date']);
	$lastpost = ($post['ulastpost'] ? timeunits(time() - $post['ulastpost']) : 'none');
	$lastview = timeunits(time() - $post['ulastview']);
	$picture = '<img src="'.profileImage($post['uname']).'" class="avatar">';
	if (!$log) $post['usignature'] = '';
	else if ($post['usignature']) {
		$post['usignature'] = '<div class="siggy">' . postfilter($post['usignature']) . '</div>';
	}
	$utitle = $post['utitle'];
	$ujoined = date('Y-m-d', $post['ujoined']);
	$posttext = postfilter($post['text']);
	$Postedon = __("Posted on");
	$Posts = __("Posts");
	$Since = __("Since");
	$Lastpost = __("Last post");
	$Lastview = __("Last view");
	return <<<HTML
<table class="c1 threadpost" id="{$post['id']}">
	$postheaderrow
	<tr>
		<td class="b n1 topbar_1">$ulink</td>
		<td class="b n1 topbar_2 fullwidth">
			<table class="fullwidth">
				<tr><td class="nb">$Postedon $pdate$threadlink $revisionstr</td><td class="nb right">$postlinks</td></tr>
			</table>
		</td>
	</tr><tr valign="top">
		<td class="b n1 sidebar">
			$utitle
			$picture
			<br>$Posts: {$post['uposts']}
			<br>
			<br>$Since: $ujoined
			<br>
			<br>$Lastpost: $lastpost
			<br>$Lastview: $lastview
		</td>
		<td class="b n2 mainbar" id="post_{$post['id']}">$posttext{$post['usignature']}</td>
	</tr>
</table>
HTML;
}
