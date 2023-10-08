<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\OrangeException;
use Orange\Database;
use Orange\NoticeType;

/**
 * Backend code for the notices page.
 *
 * @since 0.1.0
 */
class Notices
{
    private \Orange\Database $database;

    private $data;

    public function __construct(\Orange\Orange $betty)
    {
        global $auth;

        if (!$auth->isUserLoggedIn())
        {
            $betty->Notification("Please login to continue.", "/login.php");
        }

        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT * FROM notifications WHERE recipient = ?", [$auth->getUserID()]));
    }

    public function getData(): array
    {
        $noticeData = [];

        var_dump($this->data);

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
        }

        return $name;
    }

    private function typeToIntro($type)
    {
        $intro = "Generic notice by ";

        switch (NoticeType::from($type)) {
            case NoticeType::CommentSubmission:
                $intro = "Submission comment by ";
                break;
            case NoticeType::CommentProfile:
                $intro = "Profile comment by ";
                break;
            case NoticeType::CommentJournal:
                $intro = "Journal comment by ";
                break;
            case NoticeType::TakedownSubmission:
                $intro = "Your submission has been taken down.";
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