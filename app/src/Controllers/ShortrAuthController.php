<?php
/**
 * Shortr Slim - Shortr Auth Action Controller class
 *
 * Copyright (C) 2017 Frank Morgner <frank@ulan-bator.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace ShortrSlim\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuupola\Base62;

use ShortrSlim\Models\Users;
use ShortrSlim\Models\Tokens;

/**
 * Class ShortrController.
 *
 * @category Shortr
 * @package  ShortrSlim\Controllers
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
final class ShortrAuthController
{
    /**
     * Database handler
     *
     * @var private $config
     * @access private
     */
    private $config;

    /**
     * Database handler
     *
     * @var private $db
     * @access private
     */
    private $db;

    /**
     * Constructor
     *
     * @params array  $config
     * @params object $db
     */
    public function __construct(
        Manager $db,
        $config
    ) {
        $this->db = $db;
        $this->config = $config['app'];
    }


    /**
     * Generate Shorten Url Action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function authenticateAction(Request $request, Response $response)
    {
        try {
            // Check if parameter username is set
            if (null == (
                $username = filter_var(
                    $request->getParam('username'),
                    FILTER_SANITIZE_STRING
                )
            )
            ) {
                throw new \Exception(
                    'No parameter username is set. Parameter is mandatory'
                );
            }
            // Check if parameter password is set
            if (null == (
                $password = filter_var(
                    $request->getParam('password'),
                    FILTER_SANITIZE_STRING
                )
            )
            ) {
                throw new \Exception(
                    'No parameter password is set. Parameter is mandatory'
                );
            }
            // Check if username exists in database
            $users = Users::where('user', $username)->first();
            if (isset($users['password'])) {
                // Validate password
                if (false === password_verify($password, $users['password'])
                ) {
                    throw new \Exception(
                        'Unauthorized access. Credentials are false'
                    );
                }
                // Check if login is still valid
                $tokens = Tokens::where('user_id', $users['id'])
                    ->where('expired_at', '>', time())
                    ->first();
            } else {
                throw new \Exception(
                    'Unauthorized access. User not found'
                );
            }
            if ($tokens['token']) {
                return $response->withJson(
                    ['token' => $tokens['token']],
                    201,
                    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );
            }
            // Check if user is valid then create new JWT token
            if ($users['user'] && !$tokens['token']) {
                $payload = [
                    "jti"     => (new Base62)->encode(random_bytes(16)),
                    "iss"     => $this->config['serviceUrl'],
                    "iat"     => time(),
                    "exp"     => time() + ($this->config['jwtTokenLifetime']),
                    "context" => [
                        "user" => $users['user'],
                        "userId" => $users['id']
                    ]
                ];
                if (null == (
                    $jwt = JWT::encode($payload, $this->config['jwtSecret']))
                ) {
                    throw new \Exception(
                        'Cannot create token'
                    );
                }
                // Save new token
                Tokens::updateOrCreate(
                    [
                        'user_id' => $users['id']
                    ],
                    [
                        'token' => $jwt,
                        'created_at' => $payload['iat'],
                        'expired_at' => $payload['exp']
                    ]
                );
                // Return new token
                return $response->withJson(
                    ['token' => $jwt],
                    201,
                    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );
            }
        } catch (\Exception $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                400,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
            return $response;
        }
    }
}
