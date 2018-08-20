<?php
// Application middleware
// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new \Slim\Middleware\JwtAuthentication([
    "secure" => true,
    "relaxed" => ["localhost", "127.0.0.1", "dev.shortr.local"],
    "path" => "/shortr",
    "secret" => $settings['settings']['app']['jwtSecret'],
    "error" => function ($request, $response, $arguments) {
        return $response->withJson(
            ['msg' => $arguments["message"]],
            400,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
    }
]));
