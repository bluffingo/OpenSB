<?php

namespace Orange;

global $orange;

use Orange\Templating;

require_once dirname(__DIR__) . '/class/common.php';

$twig = new Templating($orange);

if (isset($_POST['apply'])) {
    $options = $orange->getLocalOptions();

    $options["skin"] = $_POST["theme"];

    setcookie("SBOPTIONS", base64_encode(json_encode($options)), 2147483647);

    $orange->Notification("Successfully changed your theme.", "/index.php", "success");
}

echo $twig->render('theme.twig');
