<?php

require_once __DIR__ . '/vendor/autoload.php';

return [
    'paths' => [
        'migrations' => 'config/Migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'coachme',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
];