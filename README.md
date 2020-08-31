<h1 align="center"> yii2-ranking </h1>

<p align="center"> The ranks extension for the Yii framework.</p>

![PHP Composer](https://github.com/JimChenWYU/yii2-ranking/workflows/PHP%20Composer/badge.svg)

## Installing

```shell
$ composer require jimchen/yii2-ranking -vvv
```

## Usage

### 配置：

```php

[
	'components' => [
		'ranking' => [
			'class' => \jimchen\ranking\RankingManager::class,
			'name' => 'test',
			'redis' => 'redis',
			'fetchNum' => 10,
			'rankingClasses' => [
				\jimchen\ranking\ranking\MonthlyRanking::class,
			],
		],
	],
]

```

### 初始化

```php

use jimchen\ranking\ranking\MonthlyRanking;

Yii::$app->ranking->import(new YourDataSource());

$monthlyRank = Yii::$app->ranking->get(MonthlyRanking::class);

$monthlyRank->rank('john'); // 获取john的排名
$monthlyRank->score('john'); // 获取john的分数
$monthlyRank->top(10); // 获取月榜分数最高的前10
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/JimChenWYU/yii2-ranking/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/JimChenWYU/yii2-ranking/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT