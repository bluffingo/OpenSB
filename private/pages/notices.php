<?php

namespace OpenSB;

global $twig, $database, $auth;

use SquareBracket\NotificationEnum;
use SquareBracket\Utilities;
use SquareBracket\UserData;

function typeToName($database, $type)
{
    $name = "generic";

    switch (NotificationEnum::from($type)) {
        case NotificationEnum::CommentUpload:
            $name = "comment_upload";
            break;
        case NotificationEnum::CommentProfile:
            $name = "comment_profile";
            break;
        case NotificationEnum::CommentJournal:
            $name = "comment_journal";
            break;
        case NotificationEnum::UploadTakedown:
            $name = "upload_takedown";
            break;
        case NotificationEnum::Follow:
            $name = "user_follow";
            break;
    }

    return $name;
}

function typeToIntro($database, $type)
{
    $intro = "Generic notice by ";

    switch (NotificationEnum::from($type)) {
        case NotificationEnum::CommentUpload:
            $intro = "Comment on your upload by ";
            break;
        case NotificationEnum::CommentProfile:
            $intro = "Comment on your profile by ";
            break;
        case NotificationEnum::CommentJournal:
            $intro = "Comment on your journal by ";
            break;
        case NotificationEnum::UploadTakedown:
            $intro = "Your upload has been taken down.";
            break;
        case NotificationEnum::Follow:
            $intro = "You have been followed by ";
            break;
    }

    return $intro;
}

function getRequiredData($database, $notice)
{
    $data = "[placeholder]";

    switch (NotificationEnum::from($notice["type"])) {
        case NotificationEnum::CommentUpload:
            $comment = $database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

            $data = $comment["comment"];
            break;

        case NotificationEnum::CommentProfile:
            $comment = $database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM channel_comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

            $data = $comment["comment"];
            break;
    }

    return $data;
}

if (!$auth->isUserLoggedIn())
{
    Utilities::bannerNotification("Please login to continue.", "/login");
}

$data = $database->fetchArray($database->query("SELECT * FROM notifications WHERE recipient = ? ORDER BY id DESC", [$auth->getUserID()]));

$noticeData = [];

foreach ($data as $notice) {
    $userData = new UserData($database, $notice["sender"]);

    $noticeData[] = [
        "id" => $notice["id"],
        "type" => typeToName($database, $notice["type"]),
        "sender" => [
            "id" => $notice["sender"],
            "info" => $userData->getUserArray(),
        ],
        "time" => $notice["timestamp"],
        "intro" => typeToIntro($database, $notice["type"]),
        "detail" => getRequiredData($database, $notice),
    ];
}

echo $twig->render('portal.twig', [
    'data' => $noticeData,
]);