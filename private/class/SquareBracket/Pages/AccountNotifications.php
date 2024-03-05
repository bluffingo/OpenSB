<?php

namespace SquareBracket\Pages;

use SquareBracket\NotificationEnum;
use SquareBracket\UserData;
use SquareBracket\Utilities;

/**
 * Backend code for the notices page.
 *
 * @since SquareBracket 1.0
 */
class AccountNotifications
{
    private \SquareBracket\Database $database;

    private $data;

    public function __construct(\SquareBracket\SquareBracket $orange)
    {
        global $auth;

        if (!$auth->isUserLoggedIn())
        {
            Utilities::Notification("Please login to continue.", "/login.php");
        }

        $this->database = $orange->getDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT * FROM notifications WHERE recipient = ? ORDER BY id DESC", [$auth->getUserID()]));
    }

    public function getData(): array
    {
        $noticeData = [];

        foreach ($this->data as $notice) {
            $userData = new UserData($this->database, $notice["sender"]);

            $noticeData[] = [
                "id" => $notice["id"],
                "type" => $this::typeToName($notice["type"]),
                "sender" => [
                    "id" => $notice["sender"],
                    "info" => $userData->getUserArray(),
                ],
                "time" => $notice["timestamp"],
                "intro" => $this::typeToIntro($notice["type"]),
                "detail" => $this::getRequiredData($notice),
            ];
        }

        return $noticeData;
    }

    private function typeToName($type)
    {
        $name = "generic";

        switch (NotificationEnum::from($type)) {
            case NotificationEnum::CommentSubmission:
                $name = "comment_submission";
                break;
            case NotificationEnum::CommentProfile:
                $name = "comment_profile";
                break;
            case NotificationEnum::CommentJournal:
                $name = "comment_journal";
                break;
            case NotificationEnum::TakedownSubmission:
                $name = "submission_takedown";
                break;
            case NotificationEnum::Follow:
                $name = "user_follow";
                break;
        }

        return $name;
    }

    private function typeToIntro($type)
    {
        $intro = "Generic notice by ";

        switch (NotificationEnum::from($type)) {
            case NotificationEnum::CommentSubmission:
                $intro = "SubmissionView comment by ";
                break;
            case NotificationEnum::CommentProfile:
                $intro = "UserProfile comment by ";
                break;
            case NotificationEnum::CommentJournal:
                $intro = "Journal comment by ";
                break;
            case NotificationEnum::TakedownSubmission:
                $intro = "Your submission has been taken down.";
                break;
            case NotificationEnum::Follow:
                $intro = "You have been followed by ";
                break;
        }

        return $intro;
    }

    private function getRequiredData($notice)
    {
        $data = "Generic, generic.";

        // THIS SHOULD PROBABLY BE A SWITCH CASE

        switch (NotificationEnum::from($notice["type"])) {
            case NotificationEnum::CommentSubmission:
                $comment = $this->database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

                $data = $comment["comment"];
                break;

            case NotificationEnum::CommentProfile:
                $comment = $this->database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM channel_comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

                $data = $comment["comment"];
                break;
        }

        return $data;
    }
}