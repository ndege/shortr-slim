<?php
/**
 * Shortr Slim - Shortr User Action Controller class
 *
 * Copyright (C) 2022 Frank Morgner <frank@ulan-bator.org>
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

use Http\Exception\InvalidArgumentException;
use Illuminate\Database\Capsule\Manager;
use Slim\Http\Request;
use Slim\Http\Response;

use ShortrSlim\Exceptions\UnauthorizedException;
use ShortrSlim\Helpers\AuthHelper;
use ShortrSlim\Helpers\ValidationHelper;
use ShortrSlim\Models\Users;

/**
 * Class ShortrClientController.
 *
 * @category Shortr
 * @package  ShortrSlim\Controllers
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class ShortrClientController
{
    /**
     * Configuration handler
     *
     * @var $config
     * @access private
     */
    private $config;

    /**
     * Database handler
     *
     * @var $db
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
     * Create new shortr client
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     * @access public
     */
    public function createClientAction(Request $request, Response $response)
    {
        try {
            // Check if scope is vaild
            $auth = new AuthHelper();
            $auth->checkScope('CREATE_CLIENT', $request);

            // Check if parameter username is set
            if (false === ($username = ($this->getParameterUsername($request)))) {
                throw new \InvalidArgumentException(
                    'No parameter username is set. It is mandatory for creating a client'
                );
            }

            if (true === Users::where('user', $username)->exists()) {
                throw new \InvalidArgumentException(
                    'User {'. $username.'} already exists at database'
                );
            }

            // Check if parameter password is set
            if (false === ($password = ($this->getParameterPassword($request)))) {
                throw new \InvalidArgumentException(
                    'No parameter password is set. It is mandatory for creating a client'
                );
            }
            $validate = new ValidationHelper();

            // Insert new client in table
            Users::insert([
                'user' => $validate->validateUsername($username),
                'password' => password_hash(
                    $validate->validatePassword($password),
                    PASSWORD_BCRYPT
                ),
                'scope' => $this->getParameterAdmin($request)
            ]);

            return $response->withJson(
                ['msg' => 'Created new client {' . $username .'}' ],
                201,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (UnauthorizedException $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                401,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (\Exception $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                400,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }

    /**
     * Changes/updates shortr client by username
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     *
     * @return \Slim\Http\Response
     * @access public
     */
    public function changeClientAction(Request $request, Response $response, array $args)
    {
        try {
            // Check if scope is vaild
            $auth = new AuthHelper();
            $auth->checkScope('CHANGE_CLIENT', $request);

            // Check if parameter 'username' exists in uri
            if (empty($args['username'])) {
                throw new \InvalidArgumentException('Parameter username not exists or valid');
            }

            // Check if parameter username exists in database
            $validate = new ValidationHelper();
            if (false === Users::where('user', $validate->validateUsername($args['username']))->exists()) {
                throw new \InvalidArgumentException(
                    'Parameter username {'. $args['username'].'} not exists at database for processing'
                );
            }

            // Iterate via parameters to check if to update
            $changeValues = [];

            if ($request->getParam('password')) {
                $changeValues['password'] = password_hash(
                    $validate->validatePassword(
                        $this->getParameterPassword($request)
                    ),
                    PASSWORD_BCRYPT
                );
            }
            if ($request->getParam('admin')) {
                $changeValues['scope'] = $this->getParameterAdmin($request);
            }

            // Update client in database if data exists
            if (count($changeValues) > 0) {
                Users::where('user', $args['username'])
                    ->update($changeValues);
                $msg = 'Updates client {' . $args['username'] .'}';
            } else {
                $msg = 'No data to updates client {' . $args['username'] . '}';
            }

            return $response->withJson(
                ['msg' => $msg],
                201,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (UnauthorizedException $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                401,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (\Exception $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                400,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }

    /**
     * Removes shortr client by username
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     *
     * @return \Slim\Http\Response
     * @access public
     */
    public function removeClientAction(Request $request, Response $response, array $args)
    {
        try {
            // Check if scope is vaild
            $auth = new AuthHelper();
            $auth->checkScope('REMOVE_CLIENT', $request);

            // Check if parameter 'username' exists in uri
            if (empty($args['username'])) {
                throw new \InvalidArgumentException('Parameter username not exists');
            }

            // Check if parameter username exists in database
            $validate = new ValidationHelper();
            if (false === Users::where('user', $validate->validateUsername($args['username']))->exists()) {
                throw new \InvalidArgumentException(
                    'Parameter username {'. $args['username'].'} not exists at database for processing'
                );
            }

            // Remove client from database
            Users::where('user', $args['username'])
                ->delete();

            return $response->withJson(
                ['msg' => 'Removes client {' . $args['username'] . '}' ],
                201,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (UnauthorizedException $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                401,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        } catch (\Exception $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                400,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }

    /**
     * Get parameter admin
     *
     * @param \Slim\Http\Request $request
     *
     * @return string    Returns json encoded scopes for admin or user.
     * @access private
     */
    private function getParameterAdmin(Request $request): string
    {
        return (
            filter_var(
                $request->getParam('admin'),
                FILTER_VALIDATE_BOOLEAN
            ) === true)
            ? json_encode(['USE_SHORTR', 'CREATE_CLIENT', 'CHANGE_CLIENT', 'REMOVE_CLIENT'])
            : json_encode(['USE_SHORTR']);
    }

    /**
     * Get parameter password and validate if is set
     *
     * @param \Slim\Http\Request $request
     *
     * @return mixed $password
     * @access private
     */
    private function getParameterPassword(Request $request)
    {
        return filter_var(
            $request->getParam('password'),
            FILTER_SANITIZE_STRING
        );
    }

    /**
     * Get parameter username and validate if is set
     *
     * @param \Slim\Http\Request $request
     *
     * @return mixed $username
     * @access private
     */
    private function getParameterUsername(Request $request)
    {
        return filter_var(
            $request->getParam('username'),
            FILTER_SANITIZE_STRING
        );
    }
}
