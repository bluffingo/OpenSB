<?php

namespace Betty;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
        chdir(__DIR__ . '/..');
        $this->skin = $requested_skin;
        $this->loader = new FilesystemLoader('skins/' . $this->skin . '/templates');
        $this->twig = new Environment($this->loader);
    }

    /**
     * Get all the available skins. Currently hardcoded to only Finalium.
     *
     * @since 0.1.0
     *
     * @return string[]
     */
    public function getAllSkins(): array
    {
        return [
            "finalium" => "skins/finalium/"
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