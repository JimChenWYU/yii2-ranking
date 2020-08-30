<?php

namespace jimchen\ranking\ranking;

use Carbon\Carbon;
use jimchen\ranking\Item;

class WeeklyRanking extends Ranking
{
	use SetExpiredAtTrait;

	/**
	 * @return string
	 */
	public function getRankingKey()
	{
		return sprintf('%s:week:%s', $this->name, date('YW'));
	}

	/**
	 * @param Item $item
	 * @return bool
	 */
	protected function ignore(Item $item)
	{
		$start = Carbon::now()->startOfWeek()->getTimestamp();
		$end = Carbon::now()->endOfWeek()->getTimestamp();
		$itemCreatedAt = $item->getCreatedAt();

		return !($itemCreatedAt >= $start && $itemCreatedAt <= $end);
	}

	/**
	 * @return int
	 */
	public function getDefaultExpiredAt()
	{
		return Carbon::now()->endOfWeek()->getTimestamp();
	}
}
