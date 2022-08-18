<?php
namespace squareBracket;

// Each corresponding language should have its local language name.
$languages = [
	'en-US' => "English (United States)",
];
if ($isDebug) {
	$languages['qps-plocm'] = "Pseudolocalization";
}