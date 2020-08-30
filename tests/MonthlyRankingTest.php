<?php

namespace tests;

use Carbon\Carbon;
use jimchen\ranking\ranking\MonthlyRanking;

class MonthlyRankingTest extends RankingTestCase
{
	protected function setUp()
	{
		parent::setUp();
		Carbon::setTestNow('2020-08-15');
	}

	public function testGetExpiredAt()
    {
        $expiredAt = strtotime(date('Y-m-d 0:0:0', strtotime('first day of next month'))) - 1;

        self::assertEquals($expiredAt, $this->manager->getRankingOf(MonthlyRanking::class)->getExpiredAt());
    }

	public function testTop10()
	{
		$r = [
			'yang',
			'kulu',
			'mike',
			'jian',
			'akira',
		];
		self::assertEquals($r, $this->manager->getRankingOf(MonthlyRanking::class)->top(10, false));
		self::assertEquals(array_reverse($r), $this->manager->getRankingOf(MonthlyRanking::class)->top(10, false, false));
    }

	public function testGetRank()
	{
		self::assertEquals(1, $this->manager->getRankingOf(MonthlyRanking::class)->rank('yang'));
		self::assertEquals(3, $this->manager->getRankingOf(MonthlyRanking::class)->rank('mike'));
		self::assertEquals(1, $this->manager->getRankingOf(MonthlyRanking::class)->rank('akira', false));
		self::assertEquals(null, $this->manager->getRankingOf(MonthlyRanking::class)->rank('koko'));
    }
}
