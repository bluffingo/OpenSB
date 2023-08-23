<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/FooterOptions.php';

if (isset($_POST['action'])) {
    $optionsArray = [
        'updated' => time(),
        'skin' => $_POST['skin'] ?? 'finalium',
    ];

    setcookie('SBOPTIONS', base64_encode(json_encode($optionsArray)), 2147483647);

    //if (!$error) {
    $betty->Notification("Options changed!", "/", "success");
    //}
}

$twig = new Templating($betty, $bettyTemplate);

echo $twig->render('footer_options.twig');