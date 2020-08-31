<?php

namespace jimchen\ranking\contracts;

use jimchen\ranking\Item;

interface IConcreteRanking extends IRanking
{
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
	public function top($num, $withScores, $desc);

	/**
	 * 获取 $member 的排行
	 *
	 * @param string $member
	 * @param string $desc
	 * @return int|null 如果不存在该用户的排名数据，返回 null。否则，返回具体的排名（整形）。
	 */
	public function rank($member, $desc);

	/**
	 * 获取 $member 的分数
	 *
	 * @param string $member
	 * @return string|null
	 */
	public function score($member);

	/**
	 * 返回参与排行的人数
	 *
	 * @return int
	 */
	public function cardinality();

	/**
	 * @param Item[] $items
	 * @return mixed
	 */
	public function import($items);

	/**
	 * 获取当前排行榜在 sorted set 中的 key 值
	 *
	 * @return string
	 */
	public function getRankingKey();

	/**
	 * 获取排行榜的过期时间，仅在大于0的时候有效
	 *
	 * @return integer
	 */
	public function getExpiredAt();

	/**
	 * 根据需要，判断是否忽略该 $item
	 *
	 * @param Item $item
	 * @return boolean
	 */
	public function ignore(Item $item);
}
