<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.131.0.199;dbname=plugn',
            'username' => 'sqlplug',
            'password' => 'vano2WmdnN3rAm1O',
            'charset' => 'utf8mb4',

            // Enable Caching of Schema to Reduce SQL Queries
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
        ],
        'session' => [ //Use Redis Database for Session Storage
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '10.131.8.103',
                'password' => 'nN3rAm1Ovano2Wmd',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => '10.131.8.103',
                'password' => 'nN3rAm1Ovano2Wmd',
                'port' => 6379,
                'database' => 1,
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'plugn',
                'password' => 'WeLoveEmailsFromPlugn!123',
                'port' => '587',
                'encryption' => 'tls',
                /*'plugins' => [
                    [
                        'class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin',
                    ],
                ],*/
            ],
        ],
    ],
];
