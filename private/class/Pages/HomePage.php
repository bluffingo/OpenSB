<?php

namespace OpenSB\class\Pages;

use OpenSB\class\CoreClasses;

class HomePage
{
    private CoreClasses $core_classes;

    public function __construct(CoreClasses $core_classes) {
        $this->core_classes = $core_classes;
    }

    public function render($request) {
        $this->core_classes->getTemplatingClass()->render("index.twig");
    }
}