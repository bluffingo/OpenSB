<?php
require('lib/common.php');

if (!$log) redirect('login.php');

$error = '';

if (isset($_POST['save_profile'])) {
	$background = isset($_POST['color_background']) ? $_POST['color_background'] : '#ffffff';
	$fontcolor = isset($_POST['color_font']) ? $_POST['color_font'] : '#ffffff';
	$titlecolor = isset($_POST['color_title_font']) ? $_POST['color_title_font'] : '#ffffff';
	$linkcolor = isset($_POST['color_links']) ? $_POST['color_links'] : '#0033CC';
	$headercolor = isset($_POST['color_header_font']) ? $_POST['color_header_font'] : '#ffffff';
	$highlightheader = isset($_POST['color_highlight_header']) ? $_POST['color_highlight_header'] : '#3399cc';
	$highlightinside = isset($_POST['color_highlight_inner']) ? $_POST['color_highlight_inner'] : '#ecf4fb';
	$regularheader = isset($_POST['color_normal_header']) ? $_POST['color_normal_header'] : '#ffa540';
	$regularinside = isset($_POST['color_normal_inner']) ? $_POST['color_normal_inner'] : '#fd7939';
	
	query("UPDATE channel_settings SET background = ?, fontcolor = ?, titlefont = ?, link = ?, headerfont = ?, highlightheader = ?, highlightinside = ?, regularheader = ?, regularinside = ? WHERE user = ?",
		[$background, $fontcolor, $titlecolor, $linkcolor, $headercolor, $highlightheader, $highlightinside, $regularheader, $regularinside, $userdata['id']]);
}

$twig = twigloader();
echo $twig->render('my_profile.twig', [
	'error' => isset($error) ? $error : null
]);
