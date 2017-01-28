<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            // Accept and parse JSON Requests
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'api\models\Agent',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'accountManager' => [ //Component for agent to manage Instagram Accounts
            'class' => 'api\components\AccountManager',
        ],
        'ownedAccountManager' => [ //Component for agent to manage Owned Instagram Accounts
            'class' => 'api\components\OwnedAccountManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [ // AuthController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/auth',
                    'pluralize' => false,
                    'patterns' => [
                        'GET login' => 'login',
                        'POST create-account' => 'create-account',
                        'POST request-reset-password' => 'request-reset-password',
                        'POST resend-verification-email' => 'resend-verification-email',
                        // OPTIONS VERBS
                        'OPTIONS login' => 'options',
                        'OPTIONS create-account' => 'options',
                        'OPTIONS request-reset-password' => 'options',
                        'OPTIONS resend-verification-email' => 'options',
                    ]
                ],
                [ // AccountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/account',
                    'patterns' => [
                        'GET' => 'list',
                        'GET stats' => 'stats',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS stats' => 'options',
                    ]
                ],
                [ // OwnedAccountController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/owned-account',
                    'patterns' => [
                        'GET' => 'list',
                        'DELETE' => 'remove-account',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // AssignmentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/assignment',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'POST' => 'add-agent',
                        'DELETE' => 'remove-agent',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // AgentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/agent',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'details',
                        'GET authkey' => 'generate-auth-key',
                        'DELETE unassign' => 'unassign',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS authkey' => 'options',
                        'OPTIONS unassign' => 'options',
                    ]
                ],
                [ // MediaController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/media',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'PATCH' => 'handle',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                    ]
                ],
                [ // ConversationController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/conversation',
                    'patterns' => [
                        'GET' => 'list',
                        'GET detail' => 'detail',
                        'PATCH' => 'handle',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS detail' => 'options',
                    ]
                ],
                [ // CommentController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/comment',
                    'patterns' => [
                        'POST' => 'post-comment',
                        'PATCH' => 'handle-comment',
                        'DELETE' => 'delete-comment',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
                [ // ActivityController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/activity',
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'personal-activity',
                        'GET on-account' => 'activity-on-account',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                        'OPTIONS on-account' => 'options',
                    ]
                ],
                [ // NoteController
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/note',
                    'patterns' => [
                        'GET' => 'list',
                        'POST' => 'create',
                        'PATCH' => 'update',
                        'DELETE' => 'delete',
                        // OPTIONS VERBS
                        'OPTIONS' => 'options',
                    ]
                ],
            ],
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
    ],
    'params' => $params,
];
