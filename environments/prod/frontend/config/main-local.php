<?php
return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'auth/<authclient:(instagram)>' => 'site/auth',
            ],
        ],
        'urlManagerAgent' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://agent.plugn.io',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];
