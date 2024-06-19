<?php
namespace OpenSB;

global $database;

define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$users = $database->fetchArray($database->query("SELECT id, name, joined FROM users ORDER BY joined ASC"));

var_dump($users);

$new_id = 0;

// internally update all user ids to be in an order more akin to join date.
// squarebracket's prod db has a gap between id205 and id1654 due to a botting incident from around june 2023
// this fixes that. poktube accs will however be in the wrong order due to poktube's db originally only storing
// dates as YYYY-MM-DD.
// i did however have to update the db in preparation for this script:
// * chaziz and squarebracket's join dates have been modified, so they stay as account #1 and #2
// * the old qobo and poktube accounts were merged into the squarebracket account.
// * renamed "dummy" accounts (for example, dummyID12 to DummyAccount-2022-04-07).
// -chaziz 6/19/2024
foreach ($users as $user) {
    $new_id++;
    var_dump($new_id);

    // do this before running: ALTER TABLE `users` ADD `new_id` INT NULL DEFAULT NULL AFTER `id`;
    $database->query("UPDATE bans SET userid = ? WHERE userid = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE channel_comments SET author = ? WHERE author = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE comments SET author = ? WHERE author = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE favorites SET user_id = ? WHERE user_id = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE invite_keys SET generated_by = ? WHERE generated_by = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE invite_keys SET claimed_by = ? WHERE claimed_by = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE journals SET author = ? WHERE author = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE journal_comments SET author = ? WHERE author = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE notifications SET recipient = ? WHERE recipient = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE notifications SET sender = ? WHERE sender = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE rating SET user = ? WHERE user = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE subscriptions SET id = ? WHERE id = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE subscriptions SET user = ? WHERE user = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE takedowns SET sender = ? WHERE sender = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE users SET new_id = ? WHERE id = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE user_old_names SET user = ? WHERE user = ?", [$new_id, $user["id"]]);
    $database->query("UPDATE videos SET author = ? WHERE author = ?", [$new_id, $user["id"]]);

    $pfpOld = SB_DYNAMIC_PATH . '/pfp/' . $user["name"] . '.png';
    $pfpNew = SB_DYNAMIC_PATH . '/pfp/' . $user["id"] . '.png';

    $bannerOld = SB_DYNAMIC_PATH . '/banners/' . $user["name"] . '.png';
    $bannerNew = SB_DYNAMIC_PATH . '/banners/' . $user["id"] . '.png';

    if (file_exists($pfpOld)) {
        if (!rename($pfpOld, $pfpNew)) {
            echo "Failed to rename profile picture from $pfpOld to $pfpNew";
        }
    }

    if (file_exists($bannerOld)) {
        if (!rename($bannerOld, $bannerNew)) {
            echo "Failed to rename banner from $bannerOld to $bannerNew";
        }
    }
}