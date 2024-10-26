<?php
namespace OpenSB;

global $database;

define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once SB_PRIVATE_PATH . `/class/common.php`;

// migrate from opensb 1.2 table schema to opensb 1.3 table schema

$database->query("RENAME TABLE `bans` TO `user_bans`");
$database->query("DROP TABLE `blacklisted_referer`");
$database->query("RENAME TABLE `channel_comments` TO `user_profile_comments`");
$database->query("RENAME TABLE `comments` TO `upload_comments`");
$database->query("RENAME TABLE `deleted_videos` TO `upload_deleted`");
$database->query("RENAME TABLE `favorites` TO `user_favorites`");
// invite_keys
$database->query("RENAME TABLE `ipbans` TO `ip_bans`");
// journals
// journal_comments
$database->query("RENAME TABLE `notifications` TO `user_notifications`");
$database->query("RENAME TABLE `passwordresets` TO `user_password_resets`");
$database->query("RENAME TABLE `rating` TO `upload_ratings`");
$database->query("DROP TABLE `site_settings`");
$database->query("RENAME TABLE `subscriptions` TO `user_follows`");
$database->query("RENAME TABLE `tag_index` TO `upload_tag_index`");
$database->query("RENAME TABLE `tag_meta` TO `upload_tag_meta`");
$database->query("RENAME TABLE `takedowns` TO `upload_takedowns`");
// users
// user_old_names
// user_staff_notes
$database->query("RENAME TABLE `videos` TO `uploads`");
$database->query("RENAME TABLE `views` TO `upload_views`");