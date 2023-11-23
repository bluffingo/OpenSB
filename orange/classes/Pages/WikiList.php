<?php

namespace Orange\Pages;

use Orange\User;
use Orange\OrangeException;
use Orange\Database;

/**
 * Backend code for the Qobo wikis page.
 *
 * @since 0.1.0
 */
class WikiList
{
    private $betty;
    public function __construct(\Orange\Orange $betty)
    {
        global $isQoboTV;

        $this->betty = $betty;
        if(!$isQoboTV) {
            $this->betty->Notification("This page is only intended for the official Qobo instance.", "/");
        }
    }

    /**
     * Returns an array containing the available Qobo wikis.
     *
     * @since 1.0
     *
     * @return array
     */
    public function getData(): array
    {
        return array(
            'chipchilla' => array(
                'title' => "Chip Chillipedia",
                'logo' => "https://chip.qobo.tv/resources/assets/Wiki.png",
                'desc' => "A wiki for Chip Chilla, the controversial Bluey-based animated cartoon. 
                Focused on the show, not its creators.",
                'url' => "https://chip.qobo.tv/",
            )
        );
    }
}