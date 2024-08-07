<?php

namespace OpenSB\Framework;

class UploadArray
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function query($order, $limit, $whereCondition = null, $params = []) {
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

        // uhhhh
        $upload_array = array_map(function($item) {
            $upload = new UploadData($this->database, $item['video_id']);
            return $upload->getData();
        }, $upload_id_array);

        return $upload_array;
    }
}