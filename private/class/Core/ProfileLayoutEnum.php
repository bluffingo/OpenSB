<?php

namespace OpenSB\class\Core;

enum ProfileLayoutEnum: int
{
    // Default profile
    case Default = 0;
    // YT Channel 1.0
    case YtChannel2008 = 1;
    // YT Channel 2.0
    case YtChannel2010 = 2;
    // YT Channel 3.0 / Cosmic Panda
    case YtChannel2012 = 3;
    // YT Channel 4.0 / One Channel
    case YtChannel2013 = 4;
}