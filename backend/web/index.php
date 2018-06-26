<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');
$host_backend = $_SERVER['HTTP_HOST'];
define('HOST_BACKEND','//'.$host_backend);
define('WEB_TYPE',isset($REQUEST_URI) ? 'admin' : '');
require(__DIR__ . '/../../config.php');
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');
require_once(__DIR__ . '/../../common/lib/global.php');
require(__DIR__ . '/../../common/config/const.php');
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);
$application = new yii\web\Application($config);
$application->run();
