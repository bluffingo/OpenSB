<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\UnorganizedFunctions;

session_destroy();

UnorganizedFunctions::Notification("Logged out!", "/", "success");