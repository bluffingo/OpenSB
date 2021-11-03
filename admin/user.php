<?php
require('lib/common.php');

$username = (isset($_GET['name']) ? $_GET['name'] : null);

$userData = fetch("SELECT * FROM users WHERE name = ?", [$username]);

$twig = twigloader();

echo $twig->render('user.twig', [
    'user' => $userData,
]);
