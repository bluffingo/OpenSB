<?php
require('conf/config.php');
// Each corresponding language should have its local language name.
$languages = [
	'en-US' => "English",
	'fr-CA' => "Français (Canada)",
	'ru-RU' => "Русский",
	'sv-SE' => "Svenska",
];
if ($isDebug) {
	$languages['qps-plocm'] = "Pseudolocalization [DEBUG ONLY]";
}