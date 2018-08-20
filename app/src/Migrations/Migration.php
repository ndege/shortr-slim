<?php
/**
 * Shortr Migrations - Shortr Migration class
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
namespace ShortrSlim\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

/**
 * Class ShortrMigration.
 *
 * @category Shortr
 * @package  ShortrSlim\Migrations
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class Migration extends AbstractMigration
{
    /**
     * Capsule
     *
     * @var \Illuminate\Database\Capsule\Manager $capsule
     * @access public
     */
    public $capsule;

    /**
     * Schema
     *
     * @var \Illuminate\Database\Schema\Builder $schema
     * @access public
     */
    public $schema;

    /**
     * Init
     *
     * @access public
     */
    public function init()
    {

        $config = require_once __DIR__.'/../../../config/settings.php';
        $dbConfig = $config['settings']['db'];

        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $dbConfig['host'],
            'port'      => (isset($dbConfig['port'])) ? $dbConfig['port'] : 3306,
            'database'  => (isset($dbConfig['prefix']))
                ? $dbConfig['prefix'].$dbConfig['database'] : $dbConfig['database'],
            'username'  => $dbConfig['username'],
            'password'  => $dbConfig['password'],
            'charset'   => (isset($dbConfig['charset']))
                ? $dbConfig['charset'] : "utf8mb4",
            'collation' => (isset($dbConfig['collation']))
                ? $dbConfig['collation'] : "utf8mb4_unicode_ci",
        ]);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
