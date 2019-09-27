<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Project\ProjectEmailItem;
use Cheppers\MiniCrm\DataTypes\Project\ProjectEmailsResponse;
use Cheppers\MiniCrm\DataTypes\Project\ProjectRequest;
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

    /**
     * @return array
     */
    public function casesGetProjectEmails()
    {
        return [
            'empty' => [
                ProjectEmailsResponse::__set_state([]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                ProjectRequest::__set_state([]),
            ],
            'basic' => [
                ProjectEmailsResponse::__set_state([
                    'Results' => [
                        0 => ProjectEmailItem::__set_state([
                            'Id' => 42,
                            'TemplateId' => 42,
                            'From' => 'Test From Email',
                            'To' => 'Test To Email',
                            'Subject' => 'Test Subject',
                            'Body' => 'Test Body',
                            'Status' => 'Test Status',
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 42,
                            'TemplateId' => 42,
                            'From' => 'Test From Email',
                            'To' => 'Test To Email',
                            'Subject' => 'Test Subject',
                            'Body' => 'Test Body',
                            'Status' => 'Test Status',
                        ],
                    ],
                    'Count' => 1
                ],
                ProjectRequest::__set_state(['id' => 42]),
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
     * @dataProvider casesGetProjectEmails
     */
    public function testGetProjectEmails(
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
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->getProjectEmails($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }


    /**
     * @return array
     */
    public function casesProjectUpdate()
    {
        return [
            'basic' => [
                [
                    'Id' => 6658,
                ],
                [
                    'Id' => 6658,
                ],
                ProjectRequest::__set_state([
                    'id' => 6658,
                    'name' => 'Test Name',
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
     * @dataProvider casesProjectUpdate
     */
    public function testUpdateProject(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            // Mocking response of inner method getProject().
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode(SingleProjectResponse::__set_state([
                    'id' => $request->id,
                    'categoryId' => 9999,
                    'contactId' => 9999,
                ]))
            ),
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $projectEndpoint = new ProjectEndpoint($client, new NullLogger());
        $projectEndpoint->setCredentials($this->clientOptions);

        $project = $projectEndpoint->updateProject($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($project, JSON_PRETTY_PRINT)
        );
    }
}
