<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

class UserDataArray implements DataArray
{
    private Database $database;
    private $array;

    public function __construct(Database $database, $order, $limit, $whereCondition = null, $params = [])
    {
        $this->database = $database;

        $query = "
        SELECT u.id
        FROM users u";

        if (!empty($whereCondition)) {
            $query .= "AND $whereCondition ";
        }

        $query .= "ORDER BY $order LIMIT $limit";

        $user_id_array = $this->database->execute($query);

        $this->array = array_map(function($item) {
            $upload = new UserData($this->database, $item['video_id']);
            return $upload->getData();
        }, $user_id_array);
    }

    public function getDataArray() {
        return $this->array;
    }
}