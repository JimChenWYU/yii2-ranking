<?php

namespace jimchen\ranking\ranking;

use Carbon\Carbon;

/**
 * Trait RankingTrait
 * @mixin Ranking
 */
trait SetExpiredAtTrait
{
    /**
     * @var int|null
     */
    protected $expiredAt;

    /**
     * @param int $expiredAt
     * @return static
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }

	/**
	 * 获取排行榜的过期时间，仅在大于0的时候有效
	 *
	 * @return int
	 */
	public function getExpiredAt()
	{
		if ($this->expiredAt === null) {
			return $this->getDefaultExpiredAt();
		}

		return $this->expiredAt;
	}

	/**
	 * 获取排行榜默认过期时间
	 *
	 * @return int
	 */
	public function getDefaultExpiredAt()
	{
		return -1;
	}
}
