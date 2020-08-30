<?php

namespace tests;

use jimchen\ranking\ranking\DailyRanking;
use jimchen\ranking\ranking\MonthlyRanking;
use jimchen\ranking\ranking\TotalRanking;
use jimchen\ranking\ranking\WeeklyRanking;
use jimchen\ranking\RankingManager;
use PHPUnit\Framework\TestCase;
use tests\Fixture\DummyMonthDataSource;
use Yii;
use yii\redis\Connection;

abstract class RankingTestCase extends TestCase
{
    /**
     * @var Connection
     */
    protected $redis;

    /**
     * @var RankingManager
     */
    protected $manager;

    protected function setUp()
    {
        $this->redis = Yii::createObject([
            'class' => Connection::class,
            'hostname' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DATABASE'),
        ]);
        $this->manager = new RankingManager([
            'redis' => $this->redis,
            'name' => 'test',
            'rankingClasses' => [
            	DailyRanking::class,
                WeeklyRanking::class,
                MonthlyRanking::class,
	            TotalRanking::class,
            ],
	        'dataSource' => new DummyMonthDataSource(),
        ]);
        $this->manager->import();
    }

    protected function tearDown()
    {
        $this->redis->flushdb();
    }
}
