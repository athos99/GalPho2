<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'fr-CH',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ffXXffXXffXXff',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'cacheFast' => [
            'class' => 'yii\caching\FileCache',
            //'serializer' => false
            //    'serializer'=>['json_encode','json_decode']
        ],

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
                'admin/<controller:\w+>/<action:\w+>/<id:\d+>' => 'admin/<controller>/<action>',
                'v/<path:.*>' => 'v/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ]
        ],
        'uploadManager' => [
            'class' => 'athos99\plupload\PluploadManager',
        ],

        'assetManager' => [
//            'bundles' => require(__DIR__ . '/assets.php'),
            'converter' => [
                'class' => 'athos99\assetparser\Converter',
                'force' => false
            ]
        ],
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
        ],
        'i18n'=>[
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/admin' => 'admin.php',
                    ],
                ],
            ],
        ]

    ],
    'params' => $params
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
