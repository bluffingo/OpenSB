<?php
// Functions related to sbNext Video Stuff.

function categoryIDToName($id) {
	if (isset($id)) {
		return match ($id) {
			0 => __('Miscellanous'),
			1 => __('Entertainment'),
			2 => __('Comedy & Humour'),
			3 => __('Gaming'),
			4 => __('News and Updates'),
			5 => __('Life'),
			6 => __('Science & Technology'),
			7 => __('Archive Dump')
		};
	} else {
		return null;
	}
}

function type_to_cat($type) {
	return match ($type) {
		'misc'	=> 0,
		'entertainment'	=> 1,
		'comedy'	=> 2,
		'gaming'	=> 3,
		'news'	=> 4,
		'life'	=> 5,
		'technology'	=> 6,
		'backup'	=> 7,
	};
}

// This is for the like-to-dislike ratio lightsaber. -Gamerappa, November 2nd 2021
function calculateRatio($number, $percent, $total){
	// If there's no ratio or dislikes, it returns 100.
	if ($total == 0 or $number == 0) {
		return 100;
	} else {
	// It returns the Like-to-dislike ratio.
    return ($percent / $total) * $number * 100;
	}
}