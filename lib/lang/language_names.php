<?php
require('conf/config.php');
// Each corresponding language should have its local language name.
$languages = [
	'en_US' => "English",
	'fr_CA' => "Français (Canada)",
	'ru_RU' => "Русский",
	'sv_SE' => "Svenska",
];
if ($isDebug) {
	$languages['qps_plocm'] = "Pseudolocalization [DEBUG ONLY]";
}