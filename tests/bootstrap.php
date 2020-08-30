<?php

// ensure we get report on all possible php errors
use Carbon\Carbon;

error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

date_default_timezone_set('Asia/Shanghai');

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
