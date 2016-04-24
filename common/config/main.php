<?php
return [
    'name' => 'PlugTo',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'instagram' => [
                    'class' => 'kotchuprik\authclient\Instagram',
                    'clientId' => 'a9d7f8aa04ce4dc5be54dcd58d821c08',
                    'clientSecret' => '33a094c3460a4fdaaa1673ee4f6462a4',
                ],
            ],
            // other clients
        ],
    ],
];
