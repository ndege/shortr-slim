<?php

namespace Tests\Api;

use Illuminate\Database\Capsule\Manager;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use ShortrSlim\Models\Shortr;

class ApiTest extends ApiTestCase
{
    /**
     * Test default redirect works.
     *
     * @access public
     */
    public function testDefaultRedirect()
    {
        $response = $this->runApp(
            'GET',
            '/'
        );
        $this->assertEquals(301, $response->getStatusCode());
        $config = require __DIR__ . '/../../config/settings.php';
        $this->assertContains(
            $config['settings']['app']['defaultRedirect'],
            $response->getHeader('Location')[0]
        );
    }

    /**
     * Test if redirect with short url works.
     *
     * @access public
     */
    public function testRedirect()
    {
        $response = $this->runApp(
            'GET',
            '/test'
        );
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertContains(
            'http://domain.tld',
            $response->getHeader('Location')[0]
        );
    }

    /**
     * Test if a not registered user get an error.
     *
     * @access public
     */
    public function testANoneRegisteredUserShouldNotBeAuthenticated()
    {
        $response = $this->runApp(
            'POST',
            '/auth',
            ['username' => 'dummy', 'password' => 'fail']
        );
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains(
            'Unauthorized access. User not found',
            (string)$response->getBody()
        );
    }

    /**
     * Test if registered user with false credentials get an error.
     *
     * @access public
     */
    public function testRegisteredUserShouldNotBeAuthenticated()
    {
        $response = $this->runApp(
            'POST',
            '/auth',
            ['username' => 'admin', 'password' => 'fail']
        );
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertContains(
            'Unauthorized access. Credentials are false',
            (string)$response->getBody()
        );
    }

    /**
     * Test if registered user with true credentials get an token.
     *
     * @access public
     */
    public function testATokenShouldBeReturnAfterAValidUserAuthentication()
    {
        // Send post request with credential for authentication.
        $response = $this->runApp(
            'POST',
            '/auth',
            ['username' => 'admin', 'password' => 'admin']
        );
        $this->assertEquals(201, $response->getStatusCode());
        // Then assert that we have three string sequence separated by dot returned
        // as a token.
        $this->assertRegExp(
            '/[a-zA-Z0-9-_](\.[a-zA-Z0-9-_])*$/',
            json_decode($response->getBody())->token
        );
    }

    /**
     * Test if a slug is created without middleware enabled.
     *
     * @access public
     */
    /*public function testASlugShouldBeReturnWithoutMiddleware()
    {
        $this->withMiddleware = false;
        $response = $this->runApp(
            'POST',
            '/shortr',
            ['url' => 'http://domain.tdl']
        );
        $this->assertEquals(201, $response->getStatusCode());
        // Then assert that we have three string sequence separated by dot returned
        // as a token.
        $this->assertRegExp(
            '/\/([a-z0-9]){1,14}$/',
            json_decode($response->getBody())->msg
        );
    }*/

    /**
     * Test if a slug failed page will be redirected to default.
     *
     * @access public
     */
    public function testASlugFailedRedirectToDefault()
    {
        $this->withMiddleware = false;
        $response = $this->runApp(
            'GET',
            '/xyz1234'
        );
        $this->assertEquals(301, $response->getStatusCode());
        // Then assert that we have a valid url in header Location.
        $this->assertRegExp(
            '/^https?:\/\/(?:[-A-Za-z0-9]+)\.?([A-Za-z]{2,6})?$/',
            ($response->getHeader('Location'))[0]
        );
    }
}
