<?php

namespace openSB;

$rawOutputRequired = true;
require_once dirname(__DIR__) . '/private/class/common.php';

if ($userbandata) {
    error(403, __("You are currently banned and cannot proceed with this action."));
}

if (!isset($_POST['subscription']) or $_POST['subscription'] == '') {
    die(); //don't output anything if this sneaky bastard didn't put anything to the comment field
}
if ($sql->result("SELECT COUNT(user) FROM subscriptions WHERE user=? AND id=?", [$_POST['subscription'], $userdata['id']]) != 0) {
    $sql->query("DELETE FROM subscriptions WHERE user=? AND id=?", [$_POST['subscription'], $userdata['id']]);
    echo __("Follow");
} else {
    $sql->query("INSERT INTO subscriptions (id, user) VALUES (?,?)", [$userdata['id'], $_POST['subscription']]);
    echo __("Unfollow");
}