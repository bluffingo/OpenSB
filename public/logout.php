<?php

namespace openSB;

global $orange;

require_once dirname(__DIR__) . '/private/class/common.php';
setcookie("SBTOKEN", "", time() - 3600);
$orange->Notification("Logged out!", "/", "success");