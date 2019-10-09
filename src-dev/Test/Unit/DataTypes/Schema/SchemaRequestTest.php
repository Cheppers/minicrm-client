<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Schema;

use Cheppers\MiniCrm\DataTypes\Schema\SchemaRequest;
use PHPUnit\Framework\TestCase;

class SchemaRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'empty' => [
                [],
                []
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $schemaRequest = new SchemaRequest();

        static::assertSame($expected, $schemaRequest->jsonSerialize());
    }
}
