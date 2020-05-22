<?php

use Minicli\App;


function getBasicApp()
{
    $config = [
        'app_path' => __DIR__ . '/../Assets/Command',
        'theme' => 'unicorn',
    ];

    return new App($config);
}
