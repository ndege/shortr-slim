<?php
/**
 * Shortr Slim - Hash Helper class
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

/**
 * Class HashHelper.
 *
 * @category Shortr
 * @package  ShortrSlim\Helper
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class HashHelper
{
    /**
     * Generate random slug hash
     *
     * @param int $length   Length of slug.
     *
     * @return string       Rendom slug hash
     * @access public
     * @todo Length and charset can be configured.
     */
    public function generateSlug($length = 6)
    {
        $char = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $char[rand(0, strlen($char) - 1)];
        }
        return $randomString;
    }
}
