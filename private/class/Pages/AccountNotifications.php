<?php

namespace Orange\Pages;

use Orange\Utilities;
use Orange\User;
use Orange\OrangeException;
use Orange\Database;
use Orange\NoticeType;

/**
 * Backend code for the notices page.
 *
 * @since Orange 1.0
 */
class AccountNotifications
{
    private \Orange\Database $database;

    private $data;

    public function __construct(\Orange\Orange $orange)
    {
        global $auth;

        if (!$auth->isUserLoggedIn())
        {
            $orange->Notification("Please login to continue.", "/login.php");
        }

        $this->database = $orange->getDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT * FROM notifications WHERE recipient = ? ORDER BY id DESC", [$auth->getUserID()]));
    }

    public function getData(): array
    {
        $noticeData = [];

        foreach ($this->data as $notice) {
            $userData = new User($this->database, $notice["sender"]);

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

        switch (NoticeType::from($type)) {
            case NoticeType::CommentSubmission:
                $name = "comment_submission";
                break;
            case NoticeType::CommentProfile:
                $name = "comment_profile";
                break;
            case NoticeType::CommentJournal:
                $name = "comment_journal";
                break;
            case NoticeType::TakedownSubmission:
                $name = "submission_takedown";
                break;
            case NoticeType::Follow:
                $name = "user_follow";
                break;
        }

        return $name;
    }

    private function typeToIntro($type)
    {
        $intro = "Generic notice by ";

        switch (NoticeType::from($type)) {
            case NoticeType::CommentSubmission:
                $intro = "SubmissionView comment by ";
                break;
            case NoticeType::CommentProfile:
                $intro = "UserProfile comment by ";
                break;
            case NoticeType::CommentJournal:
                $intro = "Journal comment by ";
                break;
            case NoticeType::TakedownSubmission:
                $intro = "Your submission has been taken down.";
                break;
            case NoticeType::Follow:
                $intro = "You have been followed by ";
                break;
        }

        return $intro;
    }

    private function getRequiredData($notice)
    {
        $data = "Generic, generic.";

        // THIS SHOULD PROBABLY BE A SWITCH CASE

        switch (NoticeType::from($notice["type"])) {
            case NoticeType::CommentSubmission:
                $comment = $this->database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

                $data = $comment["comment"];
                break;

            case NoticeType::CommentProfile:
                $comment = $this->database->fetch("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM channel_comments c WHERE c.comment_id = ?", [$notice["related_id"]]);

                $data = $comment["comment"];
                break;
        }

        return $data;
    }
}