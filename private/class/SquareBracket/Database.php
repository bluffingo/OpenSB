<?php

namespace SquareBracket;

use PDO;
use PDOException;

/**
 * PDO interface(?).
 */
class Database
{
    private $sql;

    /**
     * @throws CoreException
     */
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
            throw new CoreException('The database is currently not available. [' . $e . ']');
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

    public function getVersion()
    {
        return $this->sql->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
}