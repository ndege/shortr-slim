<?php
declare(strict_types=1);

namespace Tests\Helpers;

use ShortrSlim\Helpers\HashHelper;
use PHPUnit\Framework\TestCase;

class HashHelperTest extends TestCase
{
    /**
     * ValidationHelper object
     *
     * @var HashHelper
     * @access private
     */
    private $hash;

    public function setUp()
    {
        $this->hash = new HashHelper();
    }

    /**
     * Data provider contains correct passwords
     */
    public function slugLengthProvider()
    {
        return [
            [6],
            [8],
            [5]
        ];
    }

    /**
     * @dataProvider slugLengthProvider
     */
    public function testGenerateSlug($length)
    {
        $slug = $this->hash->generateSlug($length);
        $this->assertEquals($length, strlen($slug));
        $this->assertRegExp('/^[a-z0-9]*$/', $slug);
    }
}
