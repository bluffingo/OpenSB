<?php

namespace OpenSB;

global $auth, $database;

if (!isset($_POST['subscription']) or $_POST['subscription'] == '') {
    die(); //don't output anything if this sneaky bastard didn't put anything to the comment field
}
if ($database->result("SELECT COUNT(user) FROM user_follows WHERE user=? AND id=?", [$auth->getUserID(), $_POST['subscription']]) != 0) {
    $database->query("DELETE FROM user_follows WHERE user=? AND id=?", [$auth->getUserID(), $_POST['subscription']]);
    echo "Follow";
} else {
    $database->query("INSERT INTO user_follows (id, user) VALUES (?,?)", [$_POST['subscription'], $auth->getUserID()]);
    echo "Unfollow";
}