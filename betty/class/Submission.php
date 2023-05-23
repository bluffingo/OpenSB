<?php

namespace Betty;

use Betty\User;
use Betty\BettyException;

enum SubmissionType : int
{
    case Video = 1;
    case Image = 2;
}

class Submission
{
    private $submission;

    public function __construct()
    {
    }

    public function getSubmission()
    {
        $user = new User;
        $author = 1;
        $submission = [
            "title" => "Submission title placeholder",
            "description" => "Generic desc",
            "published" => 1,
            "type" => SubmissionType::Video,
            "file" => "/file.png",
            "author" => [
                "id" => $author,
                "info" => $user->getUserFromID($author),
            ],
            "interactions" => null,
        ];
        if (!$submission) {
            throw new BettyException('Submission does not exist');
        } else {
            $this->submission = $submission;
        }
        return $this->submission;
    }
}