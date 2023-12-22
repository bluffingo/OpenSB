<?php

namespace Orange;

global $orange;

require_once dirname(__DIR__) . '/class/common.php';
setcookie("SBTOKEN", "", time() - 3600);
$orange->Notification("Logged out!", "/", "success");