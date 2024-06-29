<?php

namespace SquareBracket;

use Parsedown;

class ParsedownExtension extends Parsedown
{

    // We don't need headers within user-written stuff.
    protected function blockHeader($Line): void
    {
        return;
    }

    protected function blockSetextHeader($Line, array $Block = null): void
    {
        return;
    }
}