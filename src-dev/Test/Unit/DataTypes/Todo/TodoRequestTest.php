<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Todo;

use Cheppers\MiniCrm\DataTypes\Todo\TodoRequest;
use PHPUnit\Framework\TestCase;

class TodoRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'TodoId' => 42,
                    'ProjectId' => 1,
                    'Comment' => 'Comment Test',
                ],
                [
                    'id' => 42,
                    'projectId' => 1,
                    'comment' => 'Comment Test',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $todoRequest = new TodoRequest();
        $todoRequest->id = $data['id'];
        $todoRequest->projectId = $data['projectId'];
        $todoRequest->comment = $data['comment'];

        static::assertSame($expected, $todoRequest->jsonSerialize());
    }
}
