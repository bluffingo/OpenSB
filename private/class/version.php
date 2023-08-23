<?php
namespace openSB;

$buildNumber = "beta-3.1";
$gitBranch = trim(substr(file_get_contents(__DIR__ . '/../../.git/' . 'HEAD'), 4));
$versionNumber = $buildNumber . "-" . "orange-dev";

?>
