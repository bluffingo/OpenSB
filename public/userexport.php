<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;
require_once dirname(__DIR__) . '/private/class/common.php';

use SpfPhp\SpfPhp;

needsLogin();

// get the complete userdata for this page.
$userdata = $sql->fetch("SELECT * FROM users WHERE id = ?", [$id]);

SpfPhp::beginCapture();
$twig = twigloader();
echo $twig->render('userexport.twig');
SpfPhp::autoRender();