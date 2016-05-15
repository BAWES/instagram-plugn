<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=plugto',
            'username' => 'plugger',
            'password' => '5397359',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'htmlLayout' => 'layouts/plugn-html',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'usernamehere',
                'password' => 'password!23',
                'port' => '587',
                'encryption' => 'tls',
                'plugins' => [
                    [
                        'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                    ],
                ],
            ],
        ],
        'session' => [ //Use Redis Database for Session Storage
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ]
        ],
    ],
];
