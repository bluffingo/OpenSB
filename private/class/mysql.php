<?php

namespace openSB;

use PDO;
use PDOException;

class MySQL
{
    private $sql;

    function __construct($host, $user, $pass, $db)
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"'
        ];
        try {
            $this->sql = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
        } catch (PDOException $e) {
            die("Error - Can't connect to database. Please try again later.");
        }
    }

    function result($query, $params = [])
    {
        $res = $this->query($query, $params);
        return $res->fetchColumn();
    }

    function query($query, $params = [])
    {
        $res = $this->sql->prepare($query);
        $res->execute($params);
        return $res;
    }

    function fetchArray($query): array
    {
        $out = [];
        while ($record = $query->fetch()) {
            $out[] = $record;
        }
        return $out;
    }

    function fetch($query, $params = [])
    {
        $res = $this->query($query, $params);
        return $res->fetch();
    }

    function insertId()
    {
        return $this->sql->lastInsertId();
    }
}