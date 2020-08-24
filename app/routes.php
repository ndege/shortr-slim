<?php

use ShortrSlim\Controllers\ShortrController;
use ShortrSlim\Controllers\ShortrAuthController;

// Routes
// Client routes
$app->put(
    '/client',
    ShortrClientController::class.':createClient'
);
$app->post(
    '/client/{slug:[0-9a-z\-_]+}',
    ShortrClientController::class.':changeClient'
);
$app->delete(
    '/client/{slug:[0-9a-z\-_]+}',
    ShortrClientController::class.':removeClient'
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
