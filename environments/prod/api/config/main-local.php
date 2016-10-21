<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManagerAgent' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://agent.plugn.io',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
];
