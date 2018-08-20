<?php
/**
 * Shortr Slim - Integrity Helper class
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
namespace ShortrSlim\Helpers;

use ShortrSlim\Models\Shortr;

/**
 * Class IntegrityHelper.
 *
 * @category Shortr
 * @package  ShortrSlim\Helper
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class IntegrityHelper
{
    /**
     * Throttle check - limit generate of shorten urls
     *
     * @param int $maxRequest
     *
     * @return boolean False if limit is not achieved
     * @access public
     */
    public function throttleCheck($maxRequest)
    {
        $this->getIp();
        $shortr = Shortr::where('ip', '=', $this->getIp())
            ->where('created_at', '>', 'CURRENT_TIMESTAMP - INTERVAL 1 hour')
            ->get();
        return count($shortr) < $maxRequest;
    }

    /**
     * Check if slug already exists
     *
     * @param string $slug  Slug to check if already exists in database
     *
     * @return boolean      False if slug not exists yet
     * @access public
     *
     */
    public function checkSlugAlreadyExists($slug)
    {
        $shortr = Shortr::where('slug', '=', $slug)->first();
        if (null == $shortr) {
            return false;
        }
        return true;
    }

     /**
     * Get IP
     *
     * @return string|null Return ip or null if no server var matched.
     * @access public
     */
    public function getIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
        ) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])
            && !empty($_SERVER['HTTP_X_REAL_IP'])
        ) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])
            && !empty($_SERVER['HTTP_CLIENT_IP'])
        ) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])
            && !empty($_SERVER['REMOTE_ADDR'])
        ) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return gethostbyname(php_uname('n'));
        }
    }
}
