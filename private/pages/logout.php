<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\UnorganizedFunctions;

session_destroy();

UnorganizedFunctions::bannerNotification("Logged out!", "/", "success");