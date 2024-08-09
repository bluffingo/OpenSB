<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

class UploadDataArray implements DataArray
{
    private Database $database;
    private $array;

    public function __construct(Database $database, $order, $limit, $whereCondition = null, $params = [])
    {
        $this->database = $database;

        $query = "
        SELECT v.video_id
        FROM videos v
        WHERE v.video_id NOT IN (SELECT submission FROM takedowns)";
        //AND v.author NOT IN (SELECT userid FROM bans)";

        if (!empty($whereCondition)) {
            $query .= "AND $whereCondition ";
        }
        // todo: blacklists and ratings and whatever
        $query .= "ORDER BY $order LIMIT $limit";

        $upload_id_array = $this->database->execute($query);

        $this->array = array_map(function($item) {
            $upload = new UploadData($this->database, $item['video_id']);
            return $upload->getData();
        }, $upload_id_array);
    }

    public function getDataArray() {
       return $this->array;
    }
}