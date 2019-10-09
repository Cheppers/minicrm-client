<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\Project\ProjectRequest;
use PHPUnit\Framework\TestCase;

class ProjectRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Id' => 42,
                    'CategoryId' => 1,
                    'ContactId' => 2,
                    'Name' => 'Test Project',
                ],
                [
                    'id' => 42,
                    'categoryId' => 1,
                    'contactId' => 2,
                    'name' => 'Test Project',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $projectRequest = new ProjectRequest();
        $projectRequest->id = $data['id'];
        $projectRequest->categoryId = $data['categoryId'];
        $projectRequest->contactId = $data['contactId'];
        $projectRequest->name = $data['name'];

        static::assertSame($expected, $projectRequest->jsonSerialize());
    }
}
