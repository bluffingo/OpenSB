<?php

namespace OpenSB;

global $orange;

use Orange\Utilities;

setcookie("SBTOKEN", "", time() - 3600);
Utilities::Notification("Logged out!", "/", "success");