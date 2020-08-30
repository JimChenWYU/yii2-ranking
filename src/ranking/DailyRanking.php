<?php

namespace jimchen\ranking\ranking;

use Carbon\Carbon;
use jimchen\ranking\Item;

class DailyRanking extends Ranking
{
	use SetExpiredAtTrait;

	/**
	 * @return string
	 */
	public function getRankingKey()
	{
		return sprintf('%s:day:%s', $this->name, date('Ymd'));
	}

	/**
	 * @param Item $item
	 * @return bool
	 */
	protected function ignore(Item $item)
	{
		$start = Carbon::now()->startOfMonth()->getTimestamp();
		$end = Carbon::now()->endOfMonth()->getTimestamp();
		$itemCreatedAt = $item->getCreatedAt();

		return !($itemCreatedAt >= $start && $itemCreatedAt <= $end);
	}

	/**
	 * @return int
	 */
	protected function getDefaultExpiredAt()
	{
		return Carbon::tomorrow()->getTimestamp();
	}
}
