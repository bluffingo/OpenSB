<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Utilities;

session_destroy();

Utilities::bannerNotification("Logged out!", "/", "success");