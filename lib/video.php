<?php

namespace squareBracket;
// Functions related to sbNext Video Stuff.
function categoryIDToName($id)
{
    switch ($id) {
        case 0:
            $name = __('Miscellanous');
            break;
        case 1:
            $name = __('Entertainment');
            break;
        case 2:
            $name = __('Comedy & Humor');
            break;
        case 3:
            $name = __('Gaming');
            break;
        case 4:
            $name = __('News and Updates');
            break;
        case 5:
            $name = __('Life');
            break;
        case 6:
            $name = __('Science & Technology');
            break;
        case 7:
            $name = __('Archive Dump');
            break;
    }
    return $name;
}

function type_to_cat($type)
{
    switch ($type) {
        case 'misc':
            $cat = 0;
            break;
        case 'entertainment':
            $cat = 1;
            break;
        case 'comedy':
            $cat = 2;
            break;
        case 'gaming':
            $cat = 3;
            break;
        case 'news':
            $cat = 4;
            break;
        case 'life':
            $cat = 5;
            break;
        case 'technology':
            $cat = 6;
            break;
        case 'backup':
            $cat = 7;
            break;
    }
    return $cat;
}

// This is for the like-to-dislike ratio lightsaber. -Gamerappa, November 2nd 2021
function calculateRatio($number, $percent, $total)
{
    // If there's no ratio or dislikes, it returns 100.
    if ($total == 0 or $number == 0) {
        return 100;
    } else {
        // It returns the Like-to-dislike ratio.
        return ($percent / $total) * $number * 100;
    }
}

/**
 * Get list of SQL SELECT fields for video data.
 *
 * @return string String to put inside a SQL statement.
 */
function videofields()
{
    $fields = ['video_id', 'title', 'description', 'time', "author", "videolength", "tags"];

    $out = '';
    foreach ($fields as $field) {
        $out .= sprintf('v.%s,', $field, $field);
    }

    $out .= "(SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views";
    return $out;
}
