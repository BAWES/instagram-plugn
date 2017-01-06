<?php
return [
    'name' => 'Plugn',
    'timeZone' => 'Asia/Kuwait',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'currencyCode' => '$',
            'defaultTimeZone' => 'Asia/Kuwait',
        ],
        'assetManager' => [
            //Link assets -> create symbolic links to assets
            'linkAssets' => true,

            //append time stamps to assets for cache busting
            //'appendTimestamp' => true,
        ],
        'slack' => [
            'class' => 'understeam\slack\Client',
            'url' => 'https://hooks.slack.com/services/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',
            'username' => 'Plugn',
        ],
        'httpclient' => [
            'class' =>'yii\httpclient\Client',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'common\components\SlackLogger',
                    'logVars' => [],
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['agency\*', 'backend\*', 'agent\*', 'frontend\*', 'common\*', 'console\*'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en',
                ],
            ],
        ],
    ],
];
