<?php

if(MAIN_ROUTE) {
    $rules = [
        'admin'    => '/customer/customer/index',
        ['pattern' => 'admin', 'route' => '/customer/customer/index', 'suffix' => '/'],

        'admin/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        'admin/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
    ];
} else {
    $rules = [];
}
$ruleArray = require_once APPLICATION_PATH . '/application/' . WEBNAME . '/main.php';
$rules = array_merge($rules,$ruleArray);
$config = [
    'defaultRoute' => '/common/news/index',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '7dNI3tFxm8I1zV7_jOLV58aRxjHmsdEG',
            'enableCsrfValidation' => false,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => $rules,
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
            'errorAction' => 'site/error',
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
        'user'=> [
            'class'=> 'backend\modules\user\UserModule',
        ],
        'settings'=> [
            'class'=> 'backend\modules\settings\SettingsModule',
        ],
	'lib' => [
	'class' => 'backend\modules\lib\LibModule',
	],
        'product' => [
	'class' => 'backend\modules\product\ProductModule',
	],
	'category' => [
	'class' => 'backend\modules\category\CategoryModule',
	],
	'system' => [
	'class' => 'backend\modules\system\SystemModule',
	],
	'common' => [
	'class' => 'backend\modules\common\CommonModule',
	],
	'ajantha' => [
	'class' => 'backend\modules\ajantha\AjanthaModule',
	],
	'abc' => [
	'class' => 'backend\modules\abc\AbcModule',
	],
	'web' => [
	'class' => 'backend\modules\web\WebModule',
	],
	'admanager' => [
	'class' => 'backend\modules\admanager\AdmanagerModule',
	],
	'admin' => [
	'class' => 'backend\modules\admin\AdminModule',
	],
	'metrixa' => [
	'class' => 'backend\modules\metrixa\MetrixaModule',
	],
	'admanger' => [
	'class' => 'backend\modules\admanger\AdmangerModule',
	],
	'payments' => [
	'class' => 'backend\modules\payments\PaymentsModule',
	],
	'store' => [
	'class' => 'backend\modules\store\StoreModule',
	],
	'company' => [
	'class' => 'backend\modules\company\CompanyModule',
	],
	'customer' => [
	'class' => 'backend\modules\customer\CustomerModule',
	],
	'star' => [
	'class' => 'backend\modules\star\StarModule',
	],
	'start' => [
	'class' => 'backend\modules\start\StartModule',
	],
	// not delete
    ],
];

if (YII_ENV != 'prod') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];
//
    $config['bootstrap'][] = 'gii';
}
return $config;
