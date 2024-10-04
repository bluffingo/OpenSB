<?php

namespace OpenSB\class\Core;

use PDO;
use PDOException;

/**
 * PDO interface(?).
 */
class Database
{
    private PDO $pdo;

    /**
     * @throws CoreException
     */
    public function __construct($database_config)
    {
        $host = $database_config["host"];
        $db = $database_config["database"];
        $user = $database_config["username"];
        $pass = $database_config["password"];

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"'
        ];
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
        } catch (PDOException $e) {
            throw new CoreException('The database appears to be unavailable. [' . $e . ']');
        }
    }

    public function result($query, $params = [])
    {
        $res = $this->query($query, $params);
        return $res->fetchColumn();
    }

    public function query($query, $params = [])
    {
        $res = $this->pdo->prepare($query);
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
        return $this->pdo->lastInsertId();
    }

    public function getVersion()
    {
        return $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
}