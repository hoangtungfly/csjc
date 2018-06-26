<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'UserModule' => [
            'class' => 'backend\modules\user\User',
        ],

    ],
    'components' => [
        'user' => array(
            // enable cookie-based authentication
            'enableAutoLogin' => true,
            'class' => 'common\core\rights\User',
            'identityClass' => 'common\core\userIdentity\UserIdentity',
            'loginUrl' => HTTP_HOST .  '/site/login',
            'identityCookie' => [ // <---- here!
                'name' => '_identityadmin',
                'httpOnly' => true,
                'domain' => DOMAIN_SERVER,
            ],
        ),
//        'session' => [
//            'name' => 'PHPBACKSESSIDADMIN',
//            'savePath' => __DIR__ . '/../../sessionsadmin',
//            'useCookies' => true,
////            'timeout' => 60 * 60 * 24,
//            'cookieParams' => array(
//                'path' => '/',
//                'domain' => DOMAIN_SERVER,
//            ),
//        ],
        'authManager' => [
            //'class' => '\common\core\rights\DbManager',
            'class' => 'yii\rbac\DbManager',
        ],
//        'authManager' => array(
//                    'class' => '\common\core\rights\DbManager',
//                    'itemTable' => 'authitem',
//                    'itemChildTable' => 'authitemchild',
//                    'assignmentTable' => 'authassignment',
//                    'rightsTable' => 'rights',
//                ),
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
    ],
    'params' => $params,
];
