<?php

namespace jimchen\ranking;

interface IDataSource
{
    /**
     * @param $lastId
     * @param $fetchNum
     * @return Item[]
     */
    public function get($lastId, $fetchNum);
}
