<?php
return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'apple-app-site-association' => 'site/apple-app-association',
                'login' => 'site/login',
                'auth/<authclient:(google|live|slack)>' => 'site/auth',
                'authmobile/<authclient:(google|live|slack)>' => 'site/authmobile',
            ],
        ],
        'urlManagerFrontend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://instagram.plugn.io',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];
