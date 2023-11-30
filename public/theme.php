<?php

namespace openSB;

global $betty;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

$twig = new Templating($betty);

if (isset($_POST['apply'])) {
    $options = $betty->getLocalOptions();

    $options["skin"] = $_POST["theme"];

    setcookie("SBOPTIONS", base64_encode(json_encode($options)), 2147483647);

    $betty->Notification("Your theme has changed.", "/index.php", "success");
}

echo $twig->render('theme.twig');
