<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\UnorganizedFunctions;

setcookie("SBTOKEN", "", time() - 3600);
UnorganizedFunctions::Notification("Logged out!", "/", "success");