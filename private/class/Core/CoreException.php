<?php

namespace OpenSB\class\Core;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class CoreException extends Exception
{
    private Run $whoops;

    public function __construct($message, $code = 500, Throwable $previous = null) {
        $this->whoops = new Run;
        $this->whoops->pushHandler(new PrettyPageHandler);
        $this->whoops->register();

        parent::__construct($message, $code, $previous);
    }

    /**
     * Display custom page for exceptions using Whoops instead of using the frontend.
     *
     * @return void
     */
    #[NoReturn] public function page(): void
    {
        $this->whoops->handleException($this);
    }
}