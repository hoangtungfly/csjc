<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => array(
            // enable cookie-based authentication
            'enableAutoLogin' => true,
            'class' => 'common\core\rights\User',
            'identityClass' => 'common\core\userIdentity\UserIdentity',
            'loginUrl' => '/home/login',
            'identityCookie' => [ // <---- here!
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => DOMAIN_SERVER,
            ],
        ),
//        'session' => [
//            'name' => 'PHPBACKSESSID',
//            'savePath' => APPLICATION_PATH . '/sessions',
//            'useCookies' => true,
//            //'timeout' => 60 * 60 * 24,
//            'cookieParams' => array(
//                'path' => '/',
//                'domain' => DOMAIN_SERVER,
//            ),
//        ],
    ],
    'params' => $params,
];
