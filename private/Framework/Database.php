<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use PDO;
use PDOException;

class Database
{
    private $db;
    private $allQueries;

    function __construct($config)
    {
        $connection = 'mysql:dbname=' . $config["database"] . ';host=' . $config["host"];

        $this->allQueries = [];

        try {
            $this->db = new PDO($connection, $config["username"], $config["password"]);
        } catch (PDOException $e) {
            die('Database fail: ' . $e);
        }
    }

    public function execute($query, $params = [], $single = false)
    {
        try {
            $result = $this->db->prepare($query);
            $this->allQueries[] = $result->queryString;
            $result->execute($params);
        } catch (PDOException $e) {
            die('Database execute fail: ' . $e);
        }

        $rows = $result->rowCount();

        if(!$rows) {
            return [];
        } elseif ($single) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function getAllQueries(): array
    {
        return $this->allQueries;
    }
}
