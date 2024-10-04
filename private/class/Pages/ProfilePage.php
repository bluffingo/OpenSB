<?php

namespace OpenSB\class\Pages;

use OpenSB\class\Core\Utilities;
use OpenSB\class\CoreClasses;

class ProfilePage
{
    private CoreClasses $core_classes;

    public function __construct(CoreClasses $core_classes) {
        $this->core_classes = $core_classes;
    }

    public function render($request) {
        // temporary
        $profile_data = [
            "id" => 1,
            "username" => "SquareBracketUser",
            "displayname" => "A SquareBracket User",
            "color" => "#420666",
            "about" => "Hello, world!",
            "joined" => 0,
            "connected" => time(),
            "is_current" => true,
            "submissions" => [],
            "journals" => [],
            "comments" => [],
            "followers" => [],
            "following" => [],
            "is_staff" => true,
            "views" => 123456789,
            "old_usernames" => [],
        ];

        $this->core_classes->getTemplatingClass()->render("profile.twig", ['data' => $profile_data]);
    }
}