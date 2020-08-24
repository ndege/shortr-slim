<?php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

use ShortrSlim\Helpers\AuthHelper;

class ApiTestCase extends TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     * @access protected
     */
    protected $withMiddleware = true;
    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array $headers
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp(
        $requestMethod,
        $requestUri,
        $requestData = null,
        $headers = []
    ) {
        // Create a mock environment for testing with
        $defaultEnv = [
            'REQUEST_METHOD' => $requestMethod,
            'REQUEST_URI' => $requestUri
        ];
        if (count($headers) > 0) {
            $defaultEnv += $headers;
        }
        $environment = Environment::mock($defaultEnv);
        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);
        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }
        // Set up a response object
        $response = new Response();
        // Use the application settings
        $settings = require __DIR__ . '/../../config/settings.php';
        // Instantiate the application
        $app = new App($settings);
        // Set up dependencies
        require __DIR__ . '/../../app/dependencies.php';
        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__ . '/../../app/middleware.php';
        }
        // Register routes
        require __DIR__ . '/../../app/routes.php';
        // Process the application
        $response = $app->process($request, $response);
        // Return the response
        return $response;
    }
}
