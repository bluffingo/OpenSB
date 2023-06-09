<?php

namespace openSB;

/**
 * @deprecated
 */
function __($string, $placeholders = [])
{
    return vsprintf($string, $placeholders);
}