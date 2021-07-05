<?php
require('lib/common.php');

setcookie("SBTOKEN", "", time() - 3600);
redirect('./');