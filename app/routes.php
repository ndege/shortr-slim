<?php

use ShortrSlim\Controllers\ShortrController;
use ShortrSlim\Controllers\ShortrAuthController;
use ShortrSlim\Controllers\ShortrClientController;

// Routes
// Client routes
$app->post(
    '/client/{username:[0-9A-Za-z_-]+}',
    ShortrClientController::class.':changeClientAction'
);
$app->delete(
    '/client/{username:[0-9A-Za-z_-]+}',
    ShortrClientController::class.':removeClientAction'
);
$app->post(
    '/client',
    ShortrClientController::class.':createClientAction'
);
// Base routes
$app->post(
    '/auth',
    ShortrAuthController::class.':authenticateAction'
);
$app->post(
    '/shortr',
    ShortrController::class.':generateAction'
);
$app->get(
    '/{slug:[0-9a-z]+}',
    ShortrController::class.':redirectAction'
);
$app->get(
    '/',
    ShortrController::class.':redirectDefaultAction'
);
