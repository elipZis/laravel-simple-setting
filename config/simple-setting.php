<?php

return [

    'repository' => [
        //The table name where to store the settings.
        'table' => 'settings',
        //The used cache configuration
        'cache' => [
            'prefix' => 'settings',
            'ttl'    => 3600
        ]
    ],

    'routing' => [
        //Should routes be available to access the settings?
        'enabled'    => true,
        //What path prefix to be used
        'prefix'     => 'setting',
        //Any middleware?
        'middleware' => [],
    ],

    'sync' => [
        //Where to statically sync the settings to
        'disc'     => env('FILESYSTEM_DRIVER', 'local'),
        //The filename to write to
        'filename' => 'settings.json',
        //Whether to automatically (re-)sync the settings to the disc with every change
        'auto'     => true
    ]
];

