<?php

namespace jimchen\ranking\ranking;

use jimchen\ranking\contracts\IRanking;
use jimchen\ranking\Item;
use yii\base\InvalidArgumentException;
use yii\redis\Connection;

abstract class Ranking implements IRanking
{
    /**
     * 排行榜名称
     *
     * @var string
     */
    protected $name;

    /**
     * @var Connection
     */
    protected $redis;

    /**
     * Ranking constructor.
     * @param string $name
     * @param Connection $redis
     */
    public function __construct($name, $redis)
    {
        $this->name = $name;
        $this->redis = $redis;
    }

    /**
     * @param $member
     * @param $score
     * @return mixed
     */
    public function addScore($member, $score)
    {
        return $this->redis->zincrby($this->getRankingKey(), $score, $member);
    }

    /**
     * @param $member
     * @param $score
     * @return mixed
     */
    public function updateScore($member, $score)
    {
        return $this->redis->zadd($this->getRankingKey(), $score, $member);
    }

    /**
     * 获取 TOP x 的用户（根据分数从大到小排序）
     *
     * 当 $withScores 的值为 true 时，返回值为：
     * [
     *  'member1' => 'score1',
     *  'member2' => 'score2',
     * ]
     *
     * 当 $withScores 的值为 false 时，返回值为：
     * [
     *  'member1',
     *  'member2'
     * ]
     *
     * @param integer $num
     * @param bool $withScores
     * @param bool $desc
     * @return array
     */
    public function top($num, $withScores = true, $desc = true)
    {
        $num = (int)$num;

        if ($num <= 0) {
            throw new InvalidArgumentException('num param must great than zero.');
        }

        if ($desc) {
        	if ($withScores) {
		        return $this->redis->zrevrange($this->getRankingKey(), 0, $num - 1, 'WITHSCORES');
	        }
	        return $this->redis->zrevrange($this->getRankingKey(), 0, $num - 1);
        }

	    if ($withScores) {
		    return $this->redis->zrange($this->getRankingKey(), 0, $num - 1, 'WITHSCORES');
	    }
	    return $this->redis->zrange($this->getRankingKey(), 0, $num - 1);
    }

    /**
     * 获取 $member 的排行
     *
     * @param string $member
     * @param string $desc
     * @return int|null 如果不存在该用户的排名数据，返回 null。否则，返回具体的排名（整形）。
     */
    public function rank($member, $desc = true)
    {
	    if ($desc) {
		    $memberRanking = $this->redis->zrevrank($this->getRankingKey(), $member);
	    } else {
	    	$memberRanking = $this->redis->zrank($this->getRankingKey(), $member);
	    }

        if ($memberRanking === null) {
            return null;
        }

        return $memberRanking + 1;
    }

    /**
     * 获取 $member 的分数
     *
     * @param string $member
     * @return string|null
     */
    public function score($member)
    {
        return $this->redis->zscore($this->getRankingKey(), $member);
    }

    /**
     * 返回参与排行的人数
     *
     * @return int
     */
    public function cardinality()
    {
        return (int)$this->redis->zcard($this->getRankingKey());
    }

    /**
     * @param Item[] $items
     * @return mixed|void
     */
    public function import($items)
    {
        foreach ($items as $item) {
            if (!$this->ignore($item)) {
                $this->redis->zadd($this->getRankingKey(), $item->getScore(), $item->getMember());
            }
        }
    }

    /**
     * 获取当前排行榜在 sorted set 中的 key 值
     *
     * @return string
     */
    abstract public function getRankingKey();

    /**
     * 获取排行榜的过期时间，仅在大于0的时候有效
     *
     * @return integer
     */
    abstract public function getExpiredAt();

    /**
     * 根据需要，判断是否忽略该 $item
     *
     * @param Item $item
     * @return boolean
     */
    abstract protected function ignore(Item $item);
}
