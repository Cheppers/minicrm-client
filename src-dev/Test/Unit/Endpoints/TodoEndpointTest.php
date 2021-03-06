<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Todo\SingleTodoResponse;
use Cheppers\MiniCrm\DataTypes\Todo\TodoListResponse;
use Cheppers\MiniCrm\DataTypes\Todo\TodoRequest;
use Cheppers\MiniCrm\Endpoints\TodoEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\TodoEndpoint
 */
class TodoEndpointTest extends MiniCrmBaseTest
{
    /**
     * @return array
     */
    public function casesTodo()
    {
        return [
            'empty' => [
                SingleTodoResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                SingleTodoResponse::__set_state([
                    'Id' => 42,
                    'Status' => 'Open',
                    'ProjectId' => 42,
                    'Comment' => 'Test Comment 42',
                    'Deadline' => '2019-12-12 12:12:12',
                    'UserId' => 'Test User ID 42',
                    'Type' => '',
                    'Url' => '',
                ]),
                [
                    'Id' => 42,
                    'Status' => 'Open',
                    'ProjectId' => 42,
                    'Comment' => 'Test Comment 42',
                    'Deadline' => '2019-12-12 12:12:12',
                    'UserId' => 'Test User ID 42',
                    'Type' => '',
                    'Url' => '',
                ],
                42
            ],
            'only-status' => [
                SingleTodoResponse::__set_state([
                    'Status' => 'Open',
                ]),
                [
                    'Status' => 'Open',
                ],
                13
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $todoId
     *
     * @throws \Exception
     *
     * @dataProvider casesTodo
     */
    public function testGetTodo(
        $expected,
        array $responseBody,
        int $todoId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $todoEndpoint = new TodoEndpoint($client, new NullLogger());
        $todoEndpoint->setCredentials($this->clientOptions);

        $todo = $todoEndpoint->get($todoId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesTodoList()
    {
        return [
            'empty' => [
                TodoListResponse::__set_state([]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                1
            ],
            'basic' => [
                TodoListResponse::__set_state([
                    'Results' => [
                        0 => SingleTodoResponse::__set_state([
                            'Id' => 1,
                            'Status' => 1,
                            'ProjectId' => 1,
                            'Comment' => 1,
                            'Deadline' => 1,
                            'UserId' => 1,
                            'Type' => 1,
                            'Url' => 1,
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 1,
                            'Status' => 1,
                            'ProjectId' => 1,
                            'Comment' => 1,
                            'Deadline' => 1,
                            'UserId' => 1,
                            'Type' => 1,
                            'Url' => 1,
                        ],
                    ],
                    'Count' => 1
                ],
                42
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $projectId
     *
     * @throws \Exception
     *
     * @dataProvider casesTodoList
     */
    public function testGetTodoList(
        $expected,
        array $responseBody,
        int $projectId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $todoEndpoint = new TodoEndpoint($client, new NullLogger());
        $todoEndpoint->setCredentials($this->clientOptions);

        $todo = $todoEndpoint->getList($projectId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesTodoCreate()
    {
        return [
            'empty' => [
                [],
                [],
                TodoRequest::__set_state([])
            ],
            'basic' => [
                [
                    'Id' => 42,
                ],
                [
                    'Id' => 42,
                ],
                TodoRequest::__set_state([
                    'projectId' => 42,
                    'comment' => 'Test Comment',
                    'deadline' => '2019-12-12 12:12:12',
                    'userId' => 'Test User ID',
                ])
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
     * @dataProvider casesTodoCreate
     */
    public function testCreateTodo(
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
        $todoEndpoint = new TodoEndpoint($client, new NullLogger());
        $todoEndpoint->setCredentials($this->clientOptions);

        $todo = $todoEndpoint->create($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesTodoUpdate()
    {
        return [
            'basic' => [
                [
                    'Id' => 6658,
                ],
                [
                    'Id' => 6658,
                ],
                TodoRequest::__set_state([
                    'id' => 6658,
                    'comment' => 'Test Comment',
                ])
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
     * @dataProvider casesTodoUpdate
     */
    public function testUpdateTodo(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            // Mocking response of inner method getTodo().
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode(SingleTodoResponse::__set_state([
                    'id' => $request->id,
                    'projectId' => 9999,
                ]))
            ),
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $todoEndpoint = new TodoEndpoint($client, new NullLogger());
        $todoEndpoint->setCredentials($this->clientOptions);

        $todo = $todoEndpoint->update($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($todo, JSON_PRETTY_PRINT)
        );
    }
}
