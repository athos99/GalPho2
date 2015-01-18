<?php

return [
    'adminEmail' => 'admin@example.com',
    'image' => [
        'format' => [
            0 => ['right' => 5],
            1 => ['width' => 200, 'height' => 200, 'crop' => true, 'sharpen' => 19, 'right' => 2],
            2 => ['height' => 200, 'max_width' => 300, 'right' => 4],
            3 => ['width' => 50, 'height' => 50, 'right' => 2],
            4 => ['width' => 100, 'height' => 100],
            5 => ['width' => 500, 'height' => 500, 'right' => 32]
        ],
        'src' => 'images/src',
        'cache' => 'images/cache'
    ],
    'right' => [
        0 => 'admin',
        1 => 'edit',
        2 => 'original',
        3 => 'large',
        4 => 'normal',
        5 => 'thumb',
    ],
    'language' => [
        'fr'=>'français',
        'en'=>'english'
    ]
];
