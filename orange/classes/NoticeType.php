<?php

namespace Orange;

enum NoticeType: int
{
    case CommentSubmission = 0;
    case CommentProfile = 1;
    case CommentJournal = 2;
    case TakedownSubmission = 3;
}