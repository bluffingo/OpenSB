<?php
require('lib/common.php');
//TODO: Add "successfully logged out" message when you log out. -gr 7/26/2021
setcookie("SBTOKEN", "", time() - 3600);
redirect('./'); //TODO: Redirect to login page when message is implemented -gr 7/26/2021
