<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes;

use Cheppers\MiniCrm\DataTypes\RequestBase;
use PHPUnit\Framework\TestCase;

class RequestBaseTestBase extends TestCase
{
    /**
     * @var string|\Cheppers\MiniCrm\DataTypes\RequestBase
     */
    protected $className = '';

    public function casesSetState()
    {
        return [];
    }

    /**
     * @dataProvider casesSetState
     */
    public function testSetState(RequestBase $expected, array $data)
    {
        static::assertEquals($expected, $this->className::__set_state($data));
    }

    public function casesJsonSerialize()
    {
        return [];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize(array $expected, RequestBase $request)
    {
        static::assertSame($expected, $request->jsonSerialize());
    }
}
