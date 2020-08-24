<?php
declare(strict_types=1);

namespace Tests\Helpers;

use ShortrSlim\Helpers\ValidationHelper;
use PHPUnit\Framework\TestCase;

class ValidationHelperTest extends TestCase
{
    /**
     * ValidationHelper object
     *
     * @var ValidationHelper
     * @access private
     */
    private $validate;

    public function setUp()
    {
        $this->validate = new ValidationHelper();
    }

    /**
     * Data provider contains correct passwords
     */
    public function passwordProvider()
    {
        return [
            ['123_*tesRFter$g'],
            ['AB_23LF§dl%#s$H'],
            ['Aw3wa5-_gt54'],
            ['b34ht.6,l32W']
        ];
    }

    /**
     * @dataProvider passwordProvider
     */
    public function testValidatePasswordIsCorrect($password)
    {
        $this->assertEquals($password, $this->validate->validatePassword($password));
    }

    /**
     * Data provider contains not sufficient complex passwords
     */
    public function incorrectPasswordProvider()
    {
        return [
            ['123_*tester$g'],
            ['AB_23L$H'],
            ['GGG$5ZZÜÖ-_3RAW'],
            ['Aw3aw3aw5gt54'],
            ['b34ht56fl32WT']
        ];
    }

    /**
     * @dataProvider incorrectPasswordProvider
     */
    public function testValidatePasswordThrowsException($password)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->validate->validatePassword($password);
    }

    /**
     * Data provider contains correct username
     */
    public function usernameProvider()
    {
        return [
            ['Christophe'],
            ['Marie-97'],
            ['admin_76'],
            ['aeon-flux_23s']
        ];
    }

    /**
     * @dataProvider usernameProvider
     */
    public function testValidateUsernameIsCorrect($username)
    {
        $this->assertEquals($username, $this->validate->validateUsername($username));
    }

    /**
     * Data provider with not correct username patterns
     */
    public function incorrectUsernameProvider()
    {
        return [
            ['123_*Tester'],
            ['ABC'],
            ['TesterÜÖ']
        ];
    }

    /**
     * @dataProvider incorrectUsernameProvider
     */
    public function testValidateUsernameThrowsException($username)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->validate->validateUsername($username);
    }
}
