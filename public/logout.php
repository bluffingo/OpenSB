<?php

namespace openSB;

global $betty;

require_once dirname(__DIR__) . '/private/class/common.php';
setcookie("SBTOKEN", "", time() - 3600);
$betty->Notification("Logged out!", "/", "success");