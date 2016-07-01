<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'urlManagerAgent' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => '/~BAWES/plugto/agent/web/index.php',
        ],
    ],
];
