<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Templating;
use SquareBracket\UnorganizedFunctions;

if (isset($_POST['apply'])) {
    $options = $orange->getLocalOptions();

    $new = explode(",", $_POST["theme"]);

    $options["skin"] = $new[0];
    $options["theme"] = $new[1];

    setcookie("SBOPTIONS", base64_encode(json_encode($options)), 2147483647);

    UnorganizedFunctions::Notification("Successfully changed your settings.", "/index.php", "success");
}

echo $twig->render('theme.twig');
