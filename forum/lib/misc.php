<?php

function mlink($name, $sort, $page, $orderby) {
	return '<a href="memberlist.php?'.
		($sort ? "sort=$sort" : '').($page != 1 ? "&page=$page" : '').
		($orderby != '' ? "&orderby=$orderby" : '').'">'
		.$name.'</a>';
}
