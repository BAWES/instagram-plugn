<?php
return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'apple-app-site-association' => 'site/apple-app-association',
                'key/<path:(instagram|billing)>/<key:\w+>' => 'site/login-auth-key',
                'auth/<authclient:(instagram)>' => 'instagram/auth',
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
