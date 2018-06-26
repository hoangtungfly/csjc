<?php

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'language'  => 'en-US',
    'resize' => [
        'user' => [
            'size' => [
                'v2' => [30, 30],
            ],
            'default' => 'v1'
        ],
        'news' => [
            'size' => [
                'v1' => [30, 30],
                'v2' => [132, 90],
                'v3' => [532, 0],
            ],
            'default' => 'v2',
        ],
        'product' => [
            'size' => [
                'v1' => [30, 30],
                'v2' => [181, 140],
                'v3' => [75, 46],
                'v4' => [450,385],
            ],
            'default' => 'v2',
        ],
        'advertise' => [
            'size' => [
                'v4' => [30, 30],
            ],
            'default' => 'v2',
        ],
        'categories' => [
            'size' => [
                'v2' => [30, 30],
            ],
            'default' => 'v2',
        ],
    ],
    'payment_config' => [
        'test_enviroment' => true,
        'allowed_currencies' => ['AUD', 'USD', 'EUR', 'GBP', 'CAD', 'CHF', 'JPY', 'SGD', 'HKD']
    ],
    'analysis_script' => array(
        'member_signup' => array(
            'google_conversion_id' => '909894071',
            'google_conversion_language' => 'en',
            'google_conversion_format' => '3',
            'google_conversion_value' => '0.5',
            'google_conversion_color' => 'ffffff',
            'google_conversion_label' => 'EekqCIHZwVoQt8PvsQM',
            'google_remarketing_only' => 'false',
        ),
        'partner_signup' => array(
            'google_conversion_id' => '909894071',
            'google_conversion_language' => 'en',
            'google_conversion_format' => '3',
            'google_conversion_value ' => '0.5',
            'google_conversion_color' => 'ffffff',
            'google_conversion_label' => '5xm-CKf8yVoQt8PvsQM',
            'google_remarketing_only' => 'false',
        ),
        'checkout' => array(
            'google_conversion_id' => '909894071',
            'google_conversion_language' => 'en',
            'google_conversion_format' => '3',
            'google_conversion_color' => 'ffffff',
            'google_conversion_label' => 'U4fLCLf6yVoQt8PvsQM',
            'google_remarketing_only' => 'false',
            'google_conversion_currency' => 'USD',
            'google_conversion_value ' => '3.00'
        ),
        'remarketing' => array(
            'google_conversion_id' => '955294277',
            'google_remarketing_only' => 'true',
        ),
        'metrixa_conversion' => array(
            'cost' => 0,
            'price' => 3,
            'quantity' => 1
        )
    ),
    'GUID'  => 'e2ce9860-a76d-4a16-9310-0b278af09147',
    
    'booking_config' => [
        'max_day_label' => '7 days',
        'max_data_value' => 60*60*24*7,
        'max_starttime_with_now' => '+1 month',
        'max_starttime_with_now_value' => '1 month',
        'max_starttime_with_now_for_js' => '+1m',
        'max_endtime_with_now_for_js' => '+1m +7d',
    ],
    'cache_file_disabled'   => [
        'useradmanager'  => 1,
        'newsadmanager'  => 1,
        'account'  => 1,
        'customers'  => 1,
        'plan'      => 1,
        'useridentity'  => 1,
        'paymentorders'  => 1,
        'paymentorderobjects' => 1,
        'notification' => 1,
        'paymentgateway' => 1,
        'kylinadwordparenttype' => 1,
        'kylinadwordtype' => 1,
        'storetoapachekylin' => 1,
        'storeparams' => 1,
        'usersendmail' => 1,
        'usrcards' => 1,
    ],
];
