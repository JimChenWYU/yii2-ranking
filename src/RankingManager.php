<?php

namespace jimchen\ranking;

use jimchen\ranking\contracts\IRanking;
use jimchen\ranking\ranking\Ranking;
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
     * @var IDataSource
     */
    public $dataSource;

    /**
     * @var Ranking[]
     */
    private $rankingObjects;

    /**
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->redis = Instance::ensure($this->redis, Connection::class);

        foreach ($this->rankingClasses as $rankingClass) {
            if ($rankingClass instanceof IRanking) {
                $this->rankingObjects[get_class($rankingClass)] = $rankingClass;
                continue;
            }
            $ref = new ReflectionClass($rankingClass);
            if ($ref->implementsInterface(IRanking::class)) {
                $object = new $rankingClass($this->name, $this->redis);
                $this->rankingObjects[$rankingClass] = $object;
            }
        }
    }

    /**
     * @param string $key
     * @return Ranking
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
     * 批量导入当前类所管理的排行类数据
     */
    public function import()
    {
        $lastId = null;

        /** @var Ranking[] $needInitObjects */
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

        if ($this->dataSource instanceof IDataSource) {
            if (!empty($needInitObjects)) {
                while (($items = $this->dataSource->get($lastId, $this->fetchNum)) != []) {
                    foreach ($needInitObjects as $ranking) {
                        $ranking->import($items);
                    }

                    if (!empty($items)) {
                        $lastId = $items[count($items)-1]->getId();
                    }
                }
            }
        }
    }
}
