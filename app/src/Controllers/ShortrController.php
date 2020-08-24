<?php
/**
 * Shortr Slim - Shortr Action Controller class
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

use Fleshgrinder\Validator;
use Illuminate\Database\Capsule\Manager;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

use ShortrSlim\Exceptions\NotFoundException;
use ShortrSlim\Helpers\HashHelper;
use ShortrSlim\Helpers\IntegrityHelper;
use ShortrSlim\Models\Shortr;

/**
 * Class ShortrController.
 *
 * @category Shortr
 * @package  ShortrSlim\Controllers
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
final class ShortrController
{
    private $config;
    private $db;
    private $logger;

    /**
     * Constructor
     *
     * @params $config
     * @params $db
     * @params $logger
     */
    public function __construct(
        Manager $db,
        LoggerInterface $logger,
        $config
    ) {
        $this->db = $db;
        $this->logger = $logger;
        $this->config = $config['app'];
    }


    /**
    * Generate Shorten Url Action
    *
    * @param \Slim\Http\Request  $request
    * @param \Slim\Http\Response $response
    *
    * @return \Slim\Http\Response
    * @throws \Exception
    */
    public function generateAction(Request $request, Response $response)
    {
        try {
            // 1. Check if parameter url is set
            if (null == ($url = $request->getParam('url'))) {
                throw new \Exception(
                    'No parameter url is set. Parameter is mandatory'
                );
            }
            // 2. Check if parameter url is not empty
            if (empty($url)) {
                throw new \Exception(
                    'Parameter url may not empty'
                );
            }
            // 3. Validate url scheme with Fleshgrinder\Validator\urls
            try {
                $instance = new Validator\URL;
                $instance->validate($url);
            } catch (\InvalidArgumentException $e) {
                throw new \Exception(
                    'No valid url given. ' . $e->getMessage()
                );
            }
            // 4. Check if service url is set
            if (null == $this->config['serviceUrl']
                || empty($this->config['serviceUrl'])
            ) {
                throw new \Exception(
                    'No service url for shortr service is set.' .
                    'Setting is mandatory'
                );
            }
            // 5. Throttle Check
            $integrity = new IntegrityHelper();
            if (isset($this->config['maxRequest'])
                && is_numeric($this->config['maxRequest'])
            ) {
                if (false == $integrity->throttleCheck($this->config['maxRequest'])
                ) {
                    throw new \Exception(
                        'Limit achived of to create shortr ulrs in given'
                        . 'interval'
                    );
                }
            }
            // 6. Check if url already exists in the database
            // If yes return it.
            $shortr = Shortr::where('url', $url)->first();
            if (isset($shortr['slug'])) {
                return $response->withJson(
                    ['msg' => $this->config['serviceUrl'] .'/'. $shortr['slug']],
                    201,
                    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                );
            }
            // 7. Create a new slug. Validate that it not exists before.
            $slugExists = true;
            $hash = new HashHelper;
            while (true === $slugExists) {
                $slug = $hash->generateSlug();
                $slugExists = $integrity->checkSlugAlreadyExists($slug);
            }
            // 8. Save slug
            Shortr::insert([
                'slug' => $slug,
                'url' => $url,
                'created_at' => date('Y-m-d G:i:s'),
                'hits' => 0,
                'ip' => $integrity->getIp()
            ]);
            return $response->withJson(
                ['msg' => $this->config['serviceUrl'] .'/'. $slug],
                201,
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
     * Redirect Action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     * @param array               $args
     *
     * @return \Slim\Http\Response
     * @throws \Exception   Slug not exists or valid.
     */
    public function redirectAction(Request $request, Response $response, $args)
    {
        try {
            // 1. Check if parameter 'slug' exists
            if (empty($args['slug']
                || 0 == preg_match('/[0-9a-Z]+/', $args['slug']))
            ) {
                throw new \Exception('slug not exists or valid');
            }
            // 2. Check if the slug exists in the database
            $shortr = Shortr::where('slug', $args['slug'])->first();
            if ($shortr == null) {
                // 2.1. Try to redirect to default
                if (isset($this->config['defaultRedirect'])
                    && strlen($this->config['defaultRedirect']) > 0) {
                    return $response->withRedirect(
                        $this->config['defaultRedirect'],
                        301
                    );
                }
                throw new NotFoundException('slug not found');
            }
            // 3. If the slug (and thus the URL) exist, update the hit counter
            Shortr::where('slug', $args['slug'])->increment('hits');
            // 4. Redirect
            return $response->withRedirect(
                $shortr['url'],
                301
            );
        } catch (NotFoundException $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                404,
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
     * Default Redirect Action
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     * @throws \Exception   Default redirect not working.
     */
    public function redirectDefaultAction(Request $request, Response $response)
    {
        try {
            if (empty($this->config['defaultRedirect'])) {
                throw new \Exception('defaultRedirect not set');
            }
            return $response->withRedirect(
                $this->config['defaultRedirect'],
                301
            );
        } catch (\Exception $e) {
            return $response->withJson(
                ['msg' => $e->getMessage()],
                400,
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }
    }
}
