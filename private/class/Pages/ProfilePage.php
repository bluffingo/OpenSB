<?php

namespace OpenSB\class\Pages;

use OpenSB\class\Core\UploadQuery;
use OpenSB\class\Core\Utilities;
use OpenSB\class\Core\ProfileLayoutEnum;
use OpenSB\class\CoreClasses;

class ProfilePage
{
    private CoreClasses $core_classes;

    public function __construct(CoreClasses $core_classes) {
        $this->core_classes = $core_classes;
    }

    public function render($request) {
        // temporary
        $profile_layout = ProfileLayoutEnum::Default;

        $upload_query = new UploadQuery($this->core_classes->getDatabaseClass());

        $user_uploads = $upload_query->query("v.time desc", 12, "v.author = ?", [1]);

        $profile_data = [
            "id" => 1,
            "username" => "SquareBracketUser",
            "displayname" => "A SquareBracket User",
            "color" => "#420666",
            "about" => "Hello, world!",
            "joined" => 0,
            "connected" => time(),
            "is_current" => true,
            "submissions" => Utilities::makeUploadArray($this->core_classes->getDatabaseClass(), $user_uploads),
            "journals" => [],
            "comments" => [],
            "followers" => 47101,
            "following" => 47101,
            "is_staff" => true,
            "views" => 123456789,
            "old_usernames" => [],
        ];

        match($profile_layout) {
            ProfileLayoutEnum::Default => $template = "profile.twig",
            ProfileLayoutEnum::YtChannel2008 => $template = "profile_yt2008.twig",
            ProfileLayoutEnum::YtChannel2010 => $template = "profile_yt2010.twig",
            ProfileLayoutEnum::YtChannel2012 => $template = "profile_yt2012.twig",
            ProfileLayoutEnum::YtChannel2013 => $template = "profile_yt2013.twig",
        };

        $this->core_classes->getTemplatingClass()->render($template, ['data' => $profile_data]);
    }
}