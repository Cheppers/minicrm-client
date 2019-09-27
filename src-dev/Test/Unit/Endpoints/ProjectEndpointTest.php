<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Project\ProjectResponse;
use Cheppers\MiniCrm\DataTypes\Project\SimpleProjectItem;
use Cheppers\MiniCrm\DataTypes\Project\SingleProjectResponse;
use Cheppers\MiniCrm\Endpoints\ProjectEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\ProjectEndpoint
 */
class ProjectEndpointTest extends MiniCrmBaseTest
{
    /**
     * @return array
     */
    public function casesProject()
    {
        return [
            'empty' => [
                SingleProjectResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                SingleProjectResponse::__set_state([
                    'Id' => 42,
                    'CategoryId' => 42,
                    'ContactId' => 42,
                    'UserId' => 'Test User Name',
                    'Name' => 'Test Name',
                    'Deleted' => 0,
                    'Url' => 'http://test.url',
                    'StatusId' => 42,
                ]),
                [
                    'Id' => 42,
                    'CategoryId' => 42,
                    'ContactId' => 42,
                    'UserId' => 'Test User Name',
                    'Name' => 'Test Name',
                    'Deleted' => 0,
                    'Url' => 'http://test.url',
                    'StatusId' => 42,
                ],
                42
            ],
            'only-status' => [
                SingleProjectResponse::__set_state([
                    'StatusId' => 42,
                ]),
                [
                    'StatusId' => 42,
                ],
                13
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
     * @dataProvider casesProject
     */
    public function testGetProject(
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
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->getProject($projectId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesGetProjectsById()
    {
        return [
            'empty' => [
                ProjectResponse::__set_state([]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                42
            ],
            'basic' => [
                ProjectResponse::__set_state([
                    'Results' => [
                        0 => SimpleProjectItem::__set_state([
                            'Id' => 42,
                            'ContactId' => 42,
                            'Name' => 'Test Simple Project Name',
                            'Url' => 'http://test.url',
                            'StatusId' => 42,
                            'UserId' => 1234,
                            'Deleted' => 0,
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 42,
                            'ContactId' => 42,
                            'Name' => 'Test Simple Project Name',
                            'Url' => 'http://test.url',
                            'StatusId' => 42,
                            'UserId' => 1234,
                            'Deleted' => 0,
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
     * @param int $id
     *
     * @throws \Exception
     *
     * @dataProvider casesGetProjectsById
     */
    public function testGetProjectsByCategoryId(
        $expected,
        array $responseBody,
        int $id
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->getProjectsByCategoryId($id);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $id
     *
     * @throws \Exception
     *
     * @dataProvider casesGetProjectsById
     */
    public function testGetProjectsByUserId(
        $expected,
        array $responseBody,
        int $id
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->getProjectsByUserId($id);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesGetProjectsByStatusGroup()
    {
        return [
            'empty' => [
                ProjectResponse::__set_state([]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                'Success',
            ],
            'basic' => [
                ProjectResponse::__set_state([
                    'Results' => [
                        0 => SimpleProjectItem::__set_state([
                            'Id' => 42,
                            'ContactId' => 42,
                            'Name' => 'Test Simple Project Name',
                            'Url' => 'http://test.url',
                            'StatusId' => 42,
                            'UserId' => 1234,
                            'Deleted' => 0,
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 42,
                            'ContactId' => 42,
                            'Name' => 'Test Simple Project Name',
                            'Url' => 'http://test.url',
                            'StatusId' => 42,
                            'UserId' => 1234,
                            'Deleted' => 0,
                        ],
                    ],
                    'Count' => 1
                ],
                'Success',
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param string $statusGroup
     *
     * @throws \Exception
     *
     * @dataProvider casesGetProjectsByStatusGroup
     */
    public function testGetProjectsByStatusGroup(
        $expected,
        array $responseBody,
        string $statusGroup
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->getProjectsByStatusGroup($statusGroup);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }
}
