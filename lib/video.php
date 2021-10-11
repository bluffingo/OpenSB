<?php
// Functions related to sbNext Video Stuff.

function categoryIDToName($id) {
    return match ($id) {
		0 => __('Miscellanous'),
        1 => __('Entertainment'),
        2 => __('Shitposting & Comedy'),
        3 => __('Gaming'),
        4 => __('News and Updates'),
        5 => __('Life'),
        6 => __('Science & Technology'),
        7 => __('Archive Dump')
    };
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