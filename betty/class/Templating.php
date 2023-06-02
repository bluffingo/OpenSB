<?php

namespace Betty;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;
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

    public function __construct(\Betty\Betty $betty, $requested_skin)
    {
        global $googleTag, $isQoboTV;
        chdir(__DIR__ . '/..');
        $this->skin = $requested_skin;
        $this->loader = new FilesystemLoader('skins/' . $this->skin . '/templates');
        $this->loader->addPath('skins/common/');
        $this->twig = new Environment($this->loader);

        $this->twig->addExtension(new BettyTwigExtension());

        $this->twig->addGlobal('google_tag', $googleTag);
        $this->twig->addGlobal('is_qobo', $isQoboTV);
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
     * @since 0.1.0
     *
     * @param $template
     * @param $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($template, $data): string
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
        ];
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
}