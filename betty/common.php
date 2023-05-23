<?php

namespace Betty;

$bettyversion = "prototype";

foreach (glob(dirname(__DIR__) . "/betty/class/*.php") as $file) {
    require_once($file);
}