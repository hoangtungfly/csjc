<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@application', str_replace('common', 'application', Yii::getAlias('@common')));
Yii::setAlias('@cache', str_replace('common', 'cache', Yii::getAlias('@common')));
Yii::setAlias('@webmain', Yii::getAlias('@application').'/' . WEBNAME);
Yii::setAlias('@runtime', APPLICATION_PATH .'/runtime');

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.DB_HOST.';dbname=' . DB_NAME,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
        ],
        'view' => [
            'class' => 'common\core\view\GlobalView',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath' => '@common/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'common\messages' => 'yii.php'
                    ]
                ],
            ],
        ],
        'language'  => 'en-US',
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => '192.168.4.51',
//                'username' => 'phongphamhong',
//                'password' => 'Abc123456',
//                'port' => '25',
//            ],
//        ],
//        'mail' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@frontend/views/mails',
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//		'host' => '192.168.21.104',
//                'username' => 'phongphamhong',
//                'password' => 'Abc123456',
//                'port' => '25',
//            ],
//        ],

        'mail' => [
            'class'            => 'zyx\phpmailer\Mailer',
            'viewPath'         => '@common/mail',
            'useFileTransport' => false,
            'config'           => [
                'mailer'     => 'smtp',
                'host'       => 'smtp.gmail.com',
                'port'       => '587',
                'smtpsecure' => 'tls',
                'smtpauth'   => true,
                'username'   => 'choxaydung1@gmail.com',
                'password'   => 'choxaydung123456',
            ],
        ],
    ],
    'timeZone'  => 'UTC',
];
