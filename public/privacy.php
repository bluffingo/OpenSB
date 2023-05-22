<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

echo twigloader()->render('_markdown.twig', [
	'pagetitle' => 'Privacy Policy',
	'file' => 'privacy_policy.md'
]);