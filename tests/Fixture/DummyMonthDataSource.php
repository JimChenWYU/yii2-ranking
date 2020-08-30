<?php

namespace tests\Fixture;

use Carbon\Carbon;
use jimchen\ranking\IDataSource;
use jimchen\ranking\Item;

class DummyMonthDataSource implements IDataSource
{
    public function get($lastId, $fetchNum)
    {
        if (is_null($lastId)) {
            return [
                // 上个月
                new Item(6, 'chen', 100, Carbon::now()->subMonth()->getTimestamp()),
                // 明天
                new Item(5, 'yang', 90, Carbon::tomorrow()->getTimestamp()),
                // 昨天的数据
                new Item(4, 'kulu', 80, Carbon::yesterday()->getTimestamp()),
                // 今日数据
                new Item(3, 'mike', 70, Carbon::today()->getTimestamp()),
                new Item(2, 'jian', 60, Carbon::today()->getTimestamp()),
                new Item(1, 'akira', 50, Carbon::today()->getTimestamp()),
            ];
        }
        return [];
    }
}
