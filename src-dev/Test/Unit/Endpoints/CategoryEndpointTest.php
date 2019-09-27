<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\DataTypes\Category\CategoryResponse;
use Cheppers\MiniCrm\DataTypes\Category\DetailedCategoryItem;
use Cheppers\MiniCrm\DataTypes\Category\DetailedCategoryResponse;
use Cheppers\MiniCrm\Endpoints\CategoryEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\CategoryEndpoint
 */
class CategoryEndpointTest extends MiniCrmBaseTest
{

    public function casesCategories()
    {
        return [
            'empty' => [
                CategoryResponse::__set_state([]),
                [],
                CategoryRequest::__set_state([])
            ],
            'basic' => [
                CategoryResponse::__set_state([
                    '1' => 'Test Category 1',
                    '2' => 'Test Category 2',
                    '3' => 'Test Category 3',
                    '4' => 'Test Category 4',
                    '5' => 'Test Category 5',
                ]),
                [
                    '1' => 'Test Category 1',
                    '2' => 'Test Category 2',
                    '3' => 'Test Category 3',
                    '4' => 'Test Category 4',
                    '5' => 'Test Category 5',
                ],
                CategoryRequest::__set_state([])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesCategories
     */
    public function testGetCategories(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $categoryEndpoint = new CategoryEndpoint($client, new NullLogger());
        $categoryEndpoint->setCredentials($this->clientOptions);

        $todo = $categoryEndpoint->getCategories($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }

    public function casesDetailedCategories()
    {
        return [
            'empty' => [
                DetailedCategoryResponse::__set_state([
                    'Results' => [],
                ]),
                [
                    'Results' => [],
                ],
                CategoryRequest::__set_state([])
            ],
            'basic' => [
                DetailedCategoryResponse::__set_state([
                    'Results' => [
                        0 => DetailedCategoryItem::__set_state([
                            'Id' => 42,
                            'OrderId' => 42,
                            'Name' => 'Test Category Name',
                            'Type' => 'Test Category Type',
                            'SenderName' => 'Test Sender Name',
                            'SenderEmail' => 'Test Sender Email',
                            'Phone' => '123456789',
                        ])
                    ]
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 42,
                            'OrderId' => 42,
                            'Name' => 'Test Category Name',
                            'Type' => 'Test Category Type',
                            'SenderName' => 'Test Sender Name',
                            'SenderEmail' => 'Test Sender Email',
                            'Phone' => '123456789',
                        ],
                    ]
                ],
                CategoryRequest::__set_state([])
            ],
        ];
    }


    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesDetailedCategories
     */
    public function testGetDetailedCategories(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $categoryEndpoint = new CategoryEndpoint($client, new NullLogger());
        $categoryEndpoint->setCredentials($this->clientOptions);

        $todo = $categoryEndpoint->getCategories($request, true);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }
}
