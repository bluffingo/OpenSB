<?php
// Functions related to sbNext Video Stuff.

function categoryIDToName($id) {
    return match ($id) {
        1 => 'Entertainment',
        2 => 'Shitposting & Comedy',
        3 => 'Gaming',
        4 => 'News and Updates',
        5 => 'Life',
        6 => 'Science & Technology',
        7 => 'Archive Dump'
    };
}