<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cacheFast' => array(
            'class' => 'yii\caching\FileCache',
            //'serializer' => false
            //    'serializer'=>array('json_encode','json_decode')
        ),

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'galpho' => ['class' => 'app\galpho\Galpho'],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'v/<path:.*>' => 'v/index',
                'admin/<controller:\w+>/<action:\w+>' => 'admin_<controller>/<action>',
                'admin/<controller:\w+>' => 'admin_<controller>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ]
        ],

        'assetManager' => array(
//            'bundles' => require(__DIR__ . '/assets.php'),
            'converter' => array(
                'class' => 'athos99\assetparser\Converter',
                'force' => false
            )
        ),
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => ['class' => 'yii\authclient\clients\GoogleOpenId'],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '186837638061934',
                    'clientSecret' => 'da329206eccb9bd1455192ede37e8896',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'IApEIlU28Nkvt5dInPVBnw',
                    'consumerSecret' => 'orqKgDeQ2itrhd8v11nKdFZ3nYAxzjiL175qVo',
                ],

            ],
        ]

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
