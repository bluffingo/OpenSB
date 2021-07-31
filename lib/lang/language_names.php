<?php
require('conf/config.php');
// Each corresponding language should have its local language name.
$languages = [
	'en-US' => "English",
	'fr-CA' => "Français Canadien",
	'ru-RU' => "Русский",
	'sv-SE' => "Svenska",
	'zh-CN' => "Simplified Chinese", //todo, use proper language name
];
if ($isDebug) {
	$languages['qps-plocm'] = "Pseudolocalization [DEBUG ONLY]";
}