<?php
return [
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'login' => 'site/login',
                'auth/<authclient:(google|live|slack)>' => 'site/auth',
                '<accountId:[0-9a-zA-Z\-&]+>/conversations' => 'dashboard/conversations',
                '<accountId:[0-9a-zA-Z\-&]+>/media' => 'dashboard/media',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];
