<?php

namespace OpenSB;

global $twig, $orange;

use OpenSB\class\Core\Utilities;

session_destroy();

Utilities::bannerNotification("Logged out!", "/", "success");