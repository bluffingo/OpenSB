<?php

namespace Betty;

class User
{
    private $user;

    public function __construct()
    {
    }

    public function getUserFromID($user_id)
    {
        $this->user = [
            "username" => "BettyUsername",
            "displayname" => "Betty Display Name",
        ];
        return $this->user;
    }
}