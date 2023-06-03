<?php

namespace Betty;

use RelativeTime\RelativeTime;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * A rewrite of openSB's /private/layout.php.
 *
 * @since 0.1.0
 */
class Templating
{
    private $skin;
    private FilesystemLoader $loader;

    public function __construct(\Betty\Betty $betty)
    {
        global $googleTag, $isQoboTV, $auth, $bettyTemplate;
        chdir(__DIR__ . '/..');
        $this->skin = $betty->getLocalOptions()["skin"] ?? $bettyTemplate;
        $this->loader = new FilesystemLoader('skins/' . $this->skin . '/templates');
        $this->loader->addPath('skins/common/');
        $this->twig = new Environment($this->loader);

        $this->twig->addExtension(new BettyTwigExtension());

        $this->twig->addGlobal('google_tag', $googleTag);
        $this->twig->addGlobal('is_qobo', $isQoboTV);
        $this->twig->addGlobal('is_user_logged_in', $auth->isUserLoggedIn());
        $this->twig->addGlobal('user_data', $auth->getUserData());
    }

    /**
     * Get all the available skins. Currently, hardcoded to only Finalium.
     *
     * @since 0.1.0
     *
     * @return string[]
     */
    public function getAllSkins(): array
    {
        return [
            "finalium" => "skins/finalium/",
        ];
    }

    /**
     * Get the skin's JSON metadata.
     *
     * @since 0.1.0
     *
     * @param $skin
     * @return array|null
     */
    public function getSkinMetadata($skin): ?array
    {
        if (file_exists($skin . "/skin.json")) {
            $metadata = file_get_contents($skin . "/skin.json");
        } else {
            trigger_error(sprintf("The metadata for Betty skin %s is missing", $skin), E_USER_WARNING);
            return null;
        }
        return json_decode($metadata, true);
    }

    /**
     * This function exists to keep compatibility with openSB pages based on twigloader.
     *
     * @param $template
     * @param array $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @since 0.1.0
     *
     */
    public function render($template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}

class BettyTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('submission_view', [$this, 'SubmissionView']),
            new TwigFunction('thumbnail', [$this, 'Thumbnail']),
            new TwigFunction('user_link', [$this, 'UserLink'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('relative_time',  [$this, 'relativeTime']),
        ];
    }

    /**
     * Relative time function.
     *
     * @since openSB Pre-Alpha 1?
     */
    function relativeTime($time): string
    {
        $config = [
            'language' => '\RelativeTime\Languages\English',
            'separator' => ', ',
            'suffix' => true,
            'truncate' => 1,
        ];

        $relativeTime = new RelativeTime($config);

        return $relativeTime->timeAgo($time);
    }

    public function SubmissionView($submission_data)
    {
        global $twig;
        if (!$submission_data) { throw new BettyException('Submission is null', 500); };
        if ($submission_data["type"] == 0)
        {
            echo $twig->render("player.twig", ['submission' => $submission_data]);
        }

        if ($submission_data["type"] == 2)
        {
            echo $twig->render("image.twig", ['submission' => $submission_data]);
        }
    }

    public function Thumbnail($id, $type)
    {
        global $isQoboTV, $storage;
        if ($type == 0)
        {
            if ($isQoboTV) {
                $data = $storage->getVideoThumbnail($id);
            } else {
                $data = "/assets/placeholder/placeholder.png";
            }
        }
        if ($type == 2)
        {
            if ($isQoboTV) {
                $data = $storage->getImageThumbnail($id);
            } else {
                $data = "/assets/placeholder/placeholder.png";
            }
        }
        return $data;
    }

    public function UserLink($user)
    {
        return <<<HTML
<a style="color: {$user["info"]["color"]}" href="user.php?name={$user["info"]["username"]}">{$user["info"]["username"]}</a>
HTML;
    }
}