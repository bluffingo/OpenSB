<?php
namespace openSB;

$buildNumber = "beta-4.0";
$gitBranch = trim(substr(file_get_contents(__DIR__ . '/../../.git/' . 'HEAD'), 4));
$versionNumber = $buildNumber . "-" . "betty-dev";

?>
