<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Template;

use Cheppers\MiniCrm\DataTypes\Template\TemplateRequest;
use PHPUnit\Framework\TestCase;

class TemplateRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Id' => 42,
                    'CategoryId' => 1,
                ],
                [
                    'id' => 42,
                    'categoryId' => 1,
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $templateRequest = new TemplateRequest();
        $templateRequest->id = $data['id'];
        $templateRequest->categoryId = $data['categoryId'];

        static::assertSame($expected, $templateRequest->jsonSerialize());
    }
}
