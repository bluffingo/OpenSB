<?php
require ('lib/common.php');

$notifsdata = query("SELECT $userfields n.*, l.id l_id, l.title l_title FROM notifications n LEFT JOIN videos l ON n.level = l.id JOIN users u ON n.sender = u.id WHERE n.recipient = ?", [$userdata['id']]);

$notifications = [];
while ($notifdata = $notifsdata->fetch())
{
    switch ($notifdata['type'])
    {
        case 1:
            $notifications[] = sprintf('%s commented on <a href="watch.php?id=%s">%s</a>.', userlink($notifdata, 'u_') , $notifdata['l_id'], $notifdata['l_title']);
			$id = $notifdata['id'];
        break;
        case 2:
            $notifications[] = sprintf('%s commented on your <a href="user.php?id=%s&forceuser">user page</a>.', userlink($notifdata, 'u_') , $userdata['id']);
			$id = $notifdata['id'];
        break;
        case 3:
            $notifications[] = sprintf('%s sent you a private message: <a href="forum/showprivate.php?id=%s">Read</a>', userlink($notifdata, 'u_') , $notifdata['level']);
			$id = $notifdata['id'];
        break;
        case 11:
            $notifications[] = sprintf('%s %s', userlink($notifdata, 'u_') , $notifdata['level']);
			$id = $notifdata['id'];
        break;
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
            $notifications[] = sprintf('%s mentioned you in a %s comment: <a href="%s.php?id=%s">Read</a>', userlink($notifdata, 'u_') , cmtNumToType($notifdata['type'] - 10) , cmtNumToType($notifdata['type'] - 10) , $notifdata['level']);
			$id = $notifdata['id'];
        break;
    }
}

$twig = twigloader();
echo $twig->render('inbox.twig', [
	'notifs' => (isset($notifications) ? $notifications : []),
	'id' => (isset($id) ? $id : []),
]);