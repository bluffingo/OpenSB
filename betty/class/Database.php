<?php

namespace Betty;

// For now, this is just the openSB MySQL class.

use PDO;
use PDOException;

class Database
{
    private $sql;

    public function __construct($host, $user, $pass, $db)
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
            throw new BettyException('The database is not available. [' . $e . ']');
        }
    }

    public function result($query, $params = [])
    {
        $res = $this->query($query, $params);
        return $res->fetchColumn();
    }

    public function query($query, $params = [])
    {
        $res = $this->sql->prepare($query);
        $res->execute($params);
        return $res;
    }

    public function fetchArray($query): array
    {
        $out = [];
        while ($record = $query->fetch()) {
            $out[] = $record;
        }
        return $out;
    }

    public function fetch($query, $params = [])
    {
        $res = $this->query($query, $params);
        return $res->fetch();
    }

    public function insertId()
    {
        return $this->sql->lastInsertId();
    }
}