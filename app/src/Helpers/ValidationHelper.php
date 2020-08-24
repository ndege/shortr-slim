<?php
/**
 * Validation Helper
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
 * Validation Helper
 *
 * @category Shortr
 * @package  ShortrSlim\Helpers
 * @author   Frank Morgner <frank@ulan-bator.org>
 * @license  https://opensource.org/licenses/MIT The MIT License
 * @link     http://ulan-bator.org
 */
class ValidationHelper
{
    /**
     * Accepted pattern for username
     *
     * @var string $acceptedPatternOfUsername
     * @access protected
     */
    protected $acceptedPatternOfUsername = "/^[a-zA-Z0-9_-]*$/";

    /**
     * Accepted pattern for password
     *
     * @var string $acceptedPatternOfPassword
     * @access protected
     */
    protected $acceptedPatternOfPassword =
        "/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&~,._\-+*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/";

    /**
     * Minimum of chars for password
     *
     * @var int $minimumLengthOfPassword
     * @access protected
     */
    protected $minimumLengthOfPassword = 12;

    /**
     * Minimum of chars for username
     *
     * @var int $minimumLengthOfPassword
     * @access protected
     */
    protected $minimumLengthOfUsername = 4;

    /**
     * Validate password for minimum of char length and hash
     *
     * @param string $password
     *
     * @return string password
     * @access private
     * @throws InvalidArgumentException
     */
    public function validatePassword(string $password): string
    {
        if (strlen($password) < $this->minimumLengthOfPassword) {
            throw new \InvalidArgumentException(
                'Minimum of char length of ' .$this->minimumLengthOfPassword. ' for password is necessary.'
            );
        }

        /** @to-do regex for password complexity */
        if (0 === preg_match($this->acceptedPatternOfPassword, $password)) {
            throw new \InvalidArgumentException(
                'Pattern of password has to consist of big and small letters, numbers and special chars.'
            );
        }
        return $password;
    }

    /**
     * Validate password for minimum of char length and hash
     *
     * @param string $username
     *
     * @return string $username
     * @access private
     * @throws InvalidArgumentException
     */
    public function validateUsername(string $username): string
    {
        if (strlen($username) < $this->minimumLengthOfUsername) {
            throw new \InvalidArgumentException(
                'Minimum of char length of ' .$this->minimumLengthOfUsername. ' for password is necessary.'
            );
        }

        //var_dump(preg_match($this->acceptedPatternOfUsername, $username)); exit;

        if (0 === preg_match($this->acceptedPatternOfUsername, $username)) {
            throw new \InvalidArgumentException(
                'Pattern of username has to consist of ' . $this->acceptedPatternOfUsername
            );
        }
        return $username;
    }
}
