<?php

return [
    'adminEmail' => 'admin@example.com',
    'image' => [
        'format' => [
            0 => ['width' => 300, 'height' => 300],
            1 => ['width' => 150, 'height' => 150, 'crop' => true, 'sharpen' => 19],
            2 => ['width' => 50, 'height' => 50],
            3 => ['width' => 100, 'height' => 100],
            4 => ['width' => 100, 'height' => 100]
        ],
        'src' => 'images/src',
        'cache' => 'images'
    ]
];
