<?php

namespace Betty;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * A rewrite of openSB's /private/layout.php.
 */
class Templating
{
    private $skin;
    private FilesystemLoader $loader;

    /**
     * @param Betty $betty
     * @param $requested_skin
     */
    public function __construct(\Betty\Betty $betty, $requested_skin)
    {
        chdir(__DIR__ . '/..');
        $this->skin = $requested_skin;
        $this->loader = new FilesystemLoader('skins/' . $this->skin);
        $this->twig = new Environment($this->loader);
    }

    /**
     * This function exists to keep compatibility with openSB pages based on twigloader.
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