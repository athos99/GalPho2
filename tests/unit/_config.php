<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../config/web.php'),
    require(__DIR__ . '/../_config.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=galpho2_test',
                'tablePrefix' => 'g2t_',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
            ],
        ],
    ]
);
