<?php

namespace openSB;

// NOT FINAL
$dateCreation = date_create("2021-04-24");
$dateToday = date_create("now");
//

$releaseNumber = date_diff($dateCreation, $dateToday)->format('%a');
$gitBranch = trim(shell_exec("git rev-parse --abbrev-ref HEAD"));
$versionNumber = $releaseNumber . "-" . $gitBranch;