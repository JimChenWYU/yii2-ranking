<?php

namespace jimchen\ranking;

use jimchen\ranking\contracts\IConcreteRanking;
use jimchen\ranking\contracts\IRanking;
use ReflectionClass;
use yii\base\Component;
use yii\di\Instance;
use yii\redis\Connection;

class RankingManager extends Component implements IRanking
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Connection
     */
    public $redis = 'redis';

	/**
	 * @var int
	 */
    public $fetchNum = 10;

    /**
     * @var string[]
     */
    public $rankingClasses;

    /**
     * @var IConcreteRanking[]
     */
    private $rankingObjects = [];

    /**
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->redis = Instance::ensure($this->redis, Connection::class);

        foreach ($this->rankingClasses as $rankingClass) {
            $this->initRankingClass($rankingClass);
        }
    }

    /**
     * @param string $key
     * @return IConcreteRanking
     */
    public function getRankingOf($key)
    {
        return $this->rankingObjects[$key];
    }

    /**
     * @param $member
     * @param $score
     * @return mixed|void
     */
    public function addScore($member, $score)
    {
        foreach ($this->rankingObjects as $rankingObject) {
            $rankingObject->addScore($member, $score);
        }
    }

    /**
     * @param $member
     * @param $score
     * @return mixed|void
     */
    public function updateScore($member, $score)
    {
        foreach ($this->rankingObjects as $rankingObject) {
            $rankingObject->updateScore($member, $score);
        }
    }

	/**
	 * @param $class
	 */
	public function addRankingClass($class)
	{
		$this->initRankingClass($class);
    }

	/**
	 * 批量导入当前类所管理的排行类数据
	 *
	 * @param IDataSource $dataSource
	 */
    public function import(IDataSource $dataSource)
    {
        $lastId = null;

        /** @var IConcreteRanking[] $needInitObjects */
        $needInitObjects = [];

        foreach ($this->rankingObjects as $ranking) {
            if (!$this->redis->exists($ranking->getRankingKey())) {
                $needInitObjects[] = $ranking;
            }
            // 设置排行榜过期时间
            if ($ranking->getExpiredAt() > 0) {
                $this->redis->expireat($ranking->getRankingKey(), $ranking->getExpiredAt());
            }
        }

	    if (!empty($needInitObjects)) {
		    while (($items = $dataSource->get($lastId, $this->fetchNum)) != []) {
			    foreach ($needInitObjects as $ranking) {
				    $ranking->import($items);
			    }

			    if (!empty($items)) {
				    $lastId = $items[count($items)-1]->getId();
			    }
		    }
	    }
    }

	private function initRankingClass($class)
	{
		if (is_string($class)) {
			if (!array_key_exists($class, $this->rankingObjects)) {
				$ref = new ReflectionClass($class);
				if ($ref->implementsInterface(IConcreteRanking::class)) {
					$object = new $class($this->name, $this->redis);
					$this->rankingClasses[] = $class;
					$this->rankingObjects[$class] = $object;
				}
			}
		}
		if (is_object($class)) {
			if (!array_key_exists($className = get_class($class), $this->rankingObjects)) {
				if ($class instanceof IConcreteRanking) {
					$this->rankingClasses[] = get_class($class);
					$this->rankingObjects[get_class($class)] = $class;
				}
			}
		}
    }
}
