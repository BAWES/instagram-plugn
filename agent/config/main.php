<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-agent',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'agent\controllers',
    'bootstrap' => ['log', 'common\components\TwoCheckoutConfig'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\Agent',
            'enableAutoLogin' => true,
        ],
        'ownedAccountManager' => [ //Component for agent to manage Owned Instagram Accounts
            'class' => 'api\components\OwnedAccountManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [ //https://console.developers.google.com/apis/library?project=plugn-1314
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '882152609344-ahm24v4mttplse2ahf35ffe4g0r6noso.apps.googleusercontent.com',
                    'clientSecret' => 'AtpqFh9Wmo4dE_sxBMeKaRaL',
                ],
                'live' => [ //https://account.live.com/developers/applications
                    'class' => 'yii\authclient\clients\Live',
                    'clientId' => '6ed789b8-d861-4e8c-8b36-3299494241bc',
                    'clientSecret' => 'WtbV3SzecgLY8VnGjwtsgaL',
                    //Manage Consent via: https://account.live.com/consent/Manage
                ],
                'slack' => [ //https://api.slack.com/apps
                    'class' => 'agent\components\SlackAuthClient',
                    'clientId' => '47737144055.58303953975',
                    'clientSecret' => 'ea30c4ae87ed4b866b9771fffc573caf',
                ],
                'instagram' => [
                    'class' => 'common\components\Instagram',
                    'clientId' => 'a9d7f8aa04ce4dc5be54dcd58d821c08',
                    'clientSecret' => '33a094c3460a4fdaaa1673ee4f6462a4',
                    'scope' => 'basic comments public_content'
                ],
            ],
        ],
        'session' => [
            'name' => 'app-agent',
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
