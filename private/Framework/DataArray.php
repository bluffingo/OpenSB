<?php

namespace OpenSB\Framework;

interface DataArray
{
    /**
     * this is where all the database fetching stuff should happen
     */
    public function __construct(Database $database, $order, $limit, $whereCondition = null, $params = []);

    /**
     * returns cleaner array to be used by the frontend
     *
     * @return array
     */
    public function getDataArray();
}