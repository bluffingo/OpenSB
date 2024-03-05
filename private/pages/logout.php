<?php

namespace OpenSB;

global $orange;

use SquareBracket\Utilities;

setcookie("SBTOKEN", "", time() - 3600);
Utilities::Notification("Logged out!", "/", "success");