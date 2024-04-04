<?php

namespace OpenSB;

global $orange;

use SquareBracket\Templating;
use SquareBracket\UnorganizedFunctions;

$twig = new Templating($orange);

if (isset($_POST['apply'])) {
    $options = $orange->getLocalOptions();

    $options["skin"] = $_POST["theme"];

    setcookie("SBOPTIONS", base64_encode(json_encode($options)), 2147483647);

    UnorganizedFunctions::Notification("Successfully changed your theme.", "/index.php", "success");
}

echo $twig->render('theme.twig');
