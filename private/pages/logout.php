<?php

namespace OpenSB;

global $orange;

use SquareBracket\UnorganizedFunctions;

setcookie("SBTOKEN", "", time() - 3600);
UnorganizedFunctions::Notification("Logged out!", "/", "success");