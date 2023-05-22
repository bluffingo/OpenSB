<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

function needsLogin() {
	global $log;
	if (!$log) {
		error('403', "This page requires login.");
	}
}