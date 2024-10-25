<?php
// YOU MIGHT BE LOOKING FOR USER.PHP -chaziz 10/25/2024
namespace OpenSB;

use SquareBracket\Utilities;

if (isset($_GET['user'])) Utilities::redirect('/user/' . $_GET['user']);