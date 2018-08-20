<?php

use ShortrSlim\Controllers\ShortrController;
use ShortrSlim\Controllers\ShortrAuthController;

// Routes
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
