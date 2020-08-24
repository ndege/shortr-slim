<?php
/**
 * Shortr Slim - Auth Helper class
 *
 * Copyright (C) 2020 Frank Morgner <frank@ulan-bator.org>
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

use Slim\Http\Request;

use ShortrSlim\Exceptions\UnauthorizedException;

/**
 * Class AuthHelper.
 *
 * @category Shortr
 * @package  ShortrSlim\Helper
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 */
class AuthHelper
{
    /**
     * Check given scope of user
     *
     * @param string $scope    Given scope to check
     * @param Request $request  Request object
     *
     * @return boolean              User has valid scope
     * @access public
     * @throws UnauthorizedException No scope defined for user
     * @throws UnauthorizedException Scope is not valid for this operation
     */
    public function checkScope($scope, Request $request)
    {
        if (null == ($userScopes = $request->getAttribute('token')->scope)) {
            throw new UnauthorizedException('No scope defined for user');
        }
        if (false === in_array($scope, $userScopes)) {
            throw new UnauthorizedException('Scope is not valid for this operation');
        }
        return true;
    }
}
