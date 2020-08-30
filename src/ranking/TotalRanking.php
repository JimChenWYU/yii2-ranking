<?php

namespace jimchen\ranking\ranking;

use jimchen\ranking\Item;

class TotalRanking extends Ranking
{
	use SetExpiredAtTrait;

	/**
	 * @return string
	 */
	public function getRankingKey()
	{
		return sprintf('%s:total', $this->name);
	}

	/**
	 * 总排行榜不忽略任何数据
	 *
	 * @param Item $item
	 * @return bool
	 */
	protected function ignore(Item $item)
	{
		return false;
	}

	/**
	 * @return int
	 */
	public function getDefaultExpiredAt()
	{
		return -1;
	}
}
