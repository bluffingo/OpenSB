<?php

namespace SquareBracket;

class Localization {
    protected $locale;
    protected $messages = [];

    public function __construct($locale = 'en-US') {
        $this->locale = $locale;
        $this->loadMessages();
    }

    protected function loadMessages(): void
    {
        $file = SB_PRIVATE_PATH . "/locales/{$this->locale}.json";
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $this->messages = json_decode($json, true);
        } else {
            trigger_error("Localization $this->locale ($file) missing", E_USER_WARNING);
        }
    }

    public function getMessage($key, ...$args) {
        if (!isset($this->messages[$key])) {
            if ($args) {
                return "[$key] (" . implode(', ', $args) . ")";
            } else {
                return "[$key]";
            }
        }

        $message = $this->messages[$key];

        foreach ($args as $arg) {
            $message = preg_replace('/%s/', $arg, $message, 1);
        }

        return $message;
    }
}