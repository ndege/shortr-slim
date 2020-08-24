<?php
$container = $app->getContainer();
// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------
// Service factory for the ORM
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

// Monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(
        $settings['logger']['path'],
        Monolog\Logger::DEBUG
    ));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------
$container[ShortrSlim\Controllers\ShortrController::class] = function ($c) {
    return new ShortrSlim\Controllers\ShortrController(
        $c->get('db'),
        $c->get('logger'),
        $c->get('settings')
    );
};
$container[ShortrSlim\Controllers\ShortrAuthController::class] = function ($c) {
    return new ShortrSlim\Controllers\ShortrAuthController(
        $c->get('db'),
        $c->get('settings')
    );
};
