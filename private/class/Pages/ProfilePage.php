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
        $profile_layout = ProfileLayoutEnum::YtChannel2008;

        $upload_query = new UploadQuery($this->core_classes->getDatabaseClass());

        $user_uploads = $upload_query->query("v.time desc", 12, "v.author = ?", [1]);

        $profile_color_data = [
            "font" => '"Comic Sans MS", "Comic Sans", cursive;',
            // https://www.youtube.com/watch?v=MldpN-L2nbc
            "yt2010_background_color" => "#CCCCCC",
            "yt2010_wrapper_color" => "#999999",
            "yt2010_wrapper_text_color" => "#000000",
            "yt2010_wrapper_link_color" => "#0000CC",
            "yt2010_wrapper_opacity" => "100",
            "yt2010_box_background_color" => "#EEEEFF",
            "yt2010_title_text_color" => "#000000",
            "yt2010_body_text_color" => "#333333",
            "yt2010_box_opacity" => "100",
            // https://www.youtube.com/watch?v=sraD_cyNQN4
            "yt2008_background_color" => "#FFFFFF",
            "yt2008_link_color" => "#0033CC",
            "yt2008_label_color" => "#666666",
            "yt2008_opacity" => "95", // pretty sure the default was 95%
            "yt2008_basic_box_border_color" => "#666666",
            "yt2008_basic_box_background_color" => "#FFFFFF",
            "yt2008_basic_box_text_color" => "#000000",
            "yt2008_highlight_box_background_color" => "#E6E6E6",
            "yt2008_highlight_box_text_color" => "#666666",
        ];

        $profile_data = [
            "id" => 1,
            "username" => "SquareBracketUser",
            "displayname" => "A SquareBracket User",
            "color" => "#420666",
            "about" => "Hello, world!",
            "joined" => 1628913600,
            "connected" => 1628913600,
            "is_current" => true,
            "submissions" => Utilities::makeUploadArray($this->core_classes->getDatabaseClass(), $user_uploads),
            "journals" => [],
            "comments" => [],
            "followers" => 47101,
            "following" => 47101,
            "is_staff" => true,
            "views" => 123456789,
            "old_usernames" => [],
            "customization" => $profile_color_data,
        ];

        switch ($profile_layout) {
            case ProfileLayoutEnum::Default:
                $template = "profile.twig";
                $page_name = "profile";
                break;

            case ProfileLayoutEnum::YtChannel2008:
                $template = "profile_yt2008.twig";
                $page_name = "profile-yt2008";
                break;

            case ProfileLayoutEnum::YtChannel2010:
                $template = "profile_yt2010.twig";
                $page_name = "profile-yt2010";
                break;
        }

        $this->core_classes->getTemplatingClass()->render($template, ['data' => $profile_data, 'page_name' => $page_name]);
    }
}