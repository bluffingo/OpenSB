<?php

namespace SquareBracket;

enum NotificationEnum: int
{
    case CommentUpload = 0;
    case CommentProfile = 1;
    case CommentJournal = 2;
    case UploadTakedown = 3;
    case Follow = 4;
}