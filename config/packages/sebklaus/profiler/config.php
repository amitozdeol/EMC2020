<?php

return [

    // Set to TRUE to enable profiling, FALSE to disable. NULL to listen to the app.debug value (default)
    'profiler' => true,

    // Set to TRUE to activate URL based Profiler enabling/ disabling (add /_profiler to the root url to activate the toggle mechanism, e.g. http://localhost/_profiler)
    'urlToggle' => false,

    // Change below urlTogglePassword from *(string) mt_rand(0, microtime(true))* to your prefered password for improved security in production environments.
    'urlTogglePassword' => \Hash::make((string) mt_rand(0, microtime(true))),

    // Either dark or light theme
    'theme' => 'dark',

    // Profiler can hide certain footer elements and be annoying. This makes it minimized by default. Set TRUE to enable.
    'minimized' => false,

    // Can use a local copy of jQuery
    'jquery_url' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',

    // Buttons: order /disable buttons
    'btns' => [
        'environment'=> ['label'=>'ENV','title'=>'Environment'],
        'memory'=>      ['label'=>'MEM','title'=>'Memory'],
        'controller'=>  ['label'=>'CTRL','title'=>'Controller'],
        'routes'=>      ['label'=>'ROUTES'],
        'log'=>         ['label'=>'LOG'],
        'sql'=>         ['label'=>'SQL'],
        'checkpoints'=> ['label'=>'TIME'],
        'file'=>        ['label'=>'FILES'],
        'view'=>        ['label'=>'VIEW'],
        'session'=>     ['label'=>'SESSION'],
        'config'=>      ['label'=>'CONFIG'],
        'storage'=>     ['label'=>'LOGS','title'=>'Logs in storage'],
        'auth'=>        ['label'=>'AUTH'],
        'auth-sentry'=> ['label'=>'AUTH']
    ],
    'doc' => 'http://www.laravel.com/docs/',

];
