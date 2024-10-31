<?php

namespace SquareBracket;

enum UserRoleEnum: int
{
    case GuestOrBanned = 0;
    case Normal = 1;
    case Moderator = 2;
    case Admin = 3;
    case Owner = 4;
}
