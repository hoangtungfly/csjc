<?php

$rules = array_merge([
        ], require_once APPLICATION_PATH . '/application/' . WEBNAME . '/main.php');
$config = [
    'defaultRoute' => '/' . WEBNAME . '/main/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'JCueP70cZzDjKjYu2h74kssesds3LJCn',
            'enableCsrfValidation' => false,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array_merge($rules, [
                WEBNAME => WEBNAME.'/main/index',
                'popupdathang.html' => '/cart/add',
                'yeuthich.html' => '/wishlist/add',
                'sosanh.html' => '/productcompare/add',
                ['pattern' => WEBNAME.'/', 'route' => WEBNAME . '/main/index', 'suffix' => '/'],
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                LINK_PUBLIC . '<partials:partials>/<controller:\w+>/<action:\w+>.html' => WEBNAME . '/<controller>/<action>',
                LINK_PUBLIC . '<partials:partials>/<controller:\w+>/<action:\w+>.json' => WEBNAME . '/<controller>/<action>',
            ]),
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
//            'basePath' => '@webroot' . DIRECTORY_MAIN_2 . '/assets',
//            'baseUrl' => '@web' . DIRECTORY_MAIN_2 . '/assets',
            'bundles' => [
                // you can override AssetBundle configs here       
                'yii\web\JqueryAsset' => [
                    'basePath' => '@webroot/assets',
                    'js' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'basePath' => '@webroot/assets',
                    'css' => [],
                ],
                'yii\web\YiiAsset' => [
                    'basePath' => '@webroot/assets',
//                    'baseUrl' => '@web',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => WEBNAME . '/main/404',
        ],
    ],
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\mongodb\debug\MongoDbPanel',
                ],
            ],
        ],
        WEBNAME => [
            'class' => 'application\\' . WEBNAME . '\\' . updateUpperFirstCharacter(WEBNAME) . 'Module',
        ],
    // not delete
    ],
];

if (YII_ENV != 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
}

return $config;
