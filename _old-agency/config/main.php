<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-agency',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'common\components\TwoCheckoutConfig'],
    'controllerNamespace' => 'agency\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\Agency',
            'enableAutoLogin' => true,
        ],
        'accountManager' => [ //Component for agent to manage Instagram Accounts
            'class' => 'agency\components\AccountManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'instagram' => [
                    'class' => 'common\components\Instagram',
                    'clientId' => 'a9d7f8aa04ce4dc5be54dcd58d821c08',
                    'clientSecret' => '33a094c3460a4fdaaa1673ee4f6462a4',
                    'scope' => 'basic comments public_content'
                ],
            ],
        ],
        'session' => [
            'name' => 'app-agency',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
