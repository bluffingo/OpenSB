<?php

namespace Betty;

class BettyException extends \Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    // use this when opensb's error function doesn't work.
    public function page() {
        $errorMsg = sprintf('<b>%s</b> (line %s in %s)', $this->getMessage(), $this->getLine(), $this->getFile());

        http_response_code(500);
        echo "<body bgcolor='purple' text='white'>";
        echo "<h1>Betty Exception</h1>";
        echo "<p>" . $errorMsg . "</p>";
        echo "</body>";
        die();
    }
}