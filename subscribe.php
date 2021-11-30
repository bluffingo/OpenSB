<?php
$rawOutputRequired = true;
require ('lib/common.php');
if ($frontend == "2008")
{
	header('Content-type: text/xml');
    if (isset($_POST['subscribe_to_user']))
    {
		$user = fetch("SELECT id FROM users WHERE name=?", [$_POST['subscribe_to_user']]);
        query("INSERT INTO subscriptions (id, user) VALUES (?,?)", [$userdata['id'], $user['id']]);
print "<?xml version='1.0' encoding='utf-8'?><root><html_content><![CDATA[<h2>Successfully subscribed!</h2>]]></html_content><return_code><![CDATA[0]]></return_code></root>";
    }
    elseif (isset($_POST['unsubscribe_from_user']))
    {
		$user = fetch("SELECT id FROM users WHERE name=?", [$_POST['unsubscribe_from_user']]);
        query("DELETE FROM subscriptions WHERE user=? AND id=?", [$user['id'], $userdata['id']]);
		print "<?xml version='1.0' encoding='utf-8'?><root><html_content><![CDATA[<h2>Unsubscribed.</h2>]]></html_content><return_code><![CDATA[0]]></return_code></root>";
    }
} else {
    if (!isset($_POST['subscription']) or $_POST['subscription'] == '')
    {
        die(); //don't output anything if this sneaky bastard didn't put anything to the comment field

    }
    if (result("SELECT COUNT(user) FROM subscriptions WHERE user=? AND id=?", [$_POST['subscription'], $userdata['id']]) != 0)
    {
        query("DELETE FROM subscriptions WHERE user=? AND id=?", [$_POST['subscription'], $userdata['id']]);
        echo __("Follow");
    }
    else
    {
        query("INSERT INTO subscriptions (id, user) VALUES (?,?)", [$userdata['id'], $_POST['subscription']]);
        echo __("Unfollow");
    }
}