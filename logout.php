<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

setcookie("SBTOKEN", "", time() - 3600);
redirect('./');