<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Category;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use PHPUnit\Framework\TestCase;

class CategoryRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Name' => 'Cheppers',
                ],
                [
                    'name' => 'Cheppers',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $categoryRequest = new CategoryRequest();
        $categoryRequest->name = $data['name'];

        static::assertSame($expected, $categoryRequest->jsonSerialize());
    }
}
