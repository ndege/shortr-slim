<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        // Monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
            'level' => \Monolog\Logger::DEBUG
        ],
        // DB settings
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'shortr',
            'username' => 'shortener',
            'password' => 'changeme',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ],
        // App Settings
        'app' => [
            // Redirect to url if no slug set
            'defaultRedirect' => 'http://localhost',
            // Baseurl of shortener service
            'serviceUrl' => 'http://localhost',
            // Maximum request allowed to set shortr urls per hour
            'maxRequest' => '10',
            // JWT secret hash
            'jwtSecret' => 'your_jwt_secret_hash',
            // Lifetime of JWT token in seconds
            'jwtTokenLifetime' => 3600 * 24
        ]
    ]
];
