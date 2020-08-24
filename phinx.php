<?php
$config = require_once __DIR__.'/config/settings.php';
return [
    'paths' => [
        'migrations' =>  __DIR__ . '/install/src/Migrations',
        'seeds' => __DIR__ .  '/install/src/Seeds'
    ],
    'migration_base_class' => '\ShortrSlim\Migrations\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => $config['settings']['db']['host'],
            'name' => $config['settings']['db']['prefix']
                . $config['settings']['db']['database'],
            'user' => $config['settings']['db']['username'],
            'pass' => $config['settings']['db']['password'],
            'port' => (isset($config['settings']['db']['port']))
                ? $config['settings']['db']['port'] : 3306,
            'charset' => $config['settings']['db']['charset']
        ],
        'local' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => $config['settings']['db']['prefix']
                . $config['settings']['db']['database'],
            'user' => $config['settings']['db']['username'],
            'pass' => $config['settings']['db']['password'],
            'port' => (isset($config['settings']['db']['port']))
                ? $config['settings']['db']['port'] : 3306,
            'charset' => $config['settings']['db']['charset']
        ]
    ]
];
