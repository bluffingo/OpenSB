<?php
require('conf/config.php');
// Each corresponding language should have its local language name.
$languages = [
	'en-US' => "English (United States)",
	'fr-CA' => "Français (Canada)",
];
if ($isDebug) {
	$languages['qps-plocm'] = "Pseudolocalization";
}