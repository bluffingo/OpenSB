<?php

namespace OpenSB\Framework;

interface Data
{
    public function __construct();
    public function getData();
    public function updateData($data);
}