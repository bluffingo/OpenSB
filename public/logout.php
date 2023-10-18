<?php

namespace openSB;

global $betty;

require_once dirname(__DIR__) . '/private/class/common.php';
//TODO: Add "successfully logged out" message when you log out. -gr 7/26/2021
setcookie("SBTOKEN", "", time() - 3600);
$betty->Notification("Logged out!", "/", "success");