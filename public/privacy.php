<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;
require_once dirname(__DIR__) . '/private/class/common.php';
use SpfPhp\SpfPhp;

SpfPhp::beginCapture();

echo twigloader()->render('_markdown.twig', [
	'pagetitle' => 'Privacy Policy',
	'file' => 'privacy_policy.md'
]);

SpfPhp::autoRender();