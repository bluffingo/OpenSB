<?php

namespace SquareBracket;

class UploadQuery
{
    private $database;
    private $whereRatings;
    private $whereTagBlacklist;

    public function __construct($database) {
        $this->database = $database;
        $this->whereRatings = Utilities::whereRatings();
        $this->whereTagBlacklist = Utilities::whereTagBlacklist();
    }

    public function query($order, $limit, $whereCondition = null, $params = []) {
        $query = "
        SELECT v.*
        FROM videos v
        WHERE v.video_id NOT IN (SELECT submission FROM takedowns)
        AND v.author NOT IN (SELECT userid FROM bans)
        ";

        if (!empty($whereCondition)) {
            $query .= "AND $whereCondition ";
        }

        if (!empty($this->whereRatings)) {
            $query .= "AND $this->whereRatings ";
        }

        if (!empty($this->whereTagBlacklist)) {
            $query .= "AND $this->whereTagBlacklist ";
        }

        $query .= "ORDER BY $order LIMIT $limit";

        return $this->database->fetchArray($this->database->query($query, $params));
    }

    // used in the browse page
    public function count($whereCondition = null, $params = []) {
        $query = "
        SELECT COUNT(*)
        FROM videos v
        WHERE v.video_id NOT IN (SELECT submission FROM takedowns)
        AND v.author NOT IN (SELECT userid FROM bans)
        ";

        if (!empty($whereCondition)) {
            $query .= "AND $whereCondition ";
        }

        if (!empty($this->whereRatings)) {
            $query .= "AND $this->whereRatings ";
        }

        if (!empty($this->whereTagBlacklist)) {
            $query .= "AND $this->whereTagBlacklist ";
        }

        return $this->database->result($query, $params);
    }
}