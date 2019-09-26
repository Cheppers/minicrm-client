<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse;
use Cheppers\MiniCrm\Endpoints\SchemaEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\SchemaEndpoint
 */
class SchemaEndpointTest extends MiniCrmBaseTest
{

    /**
     * @return array
     */
    public function casesProjectSchema()
    {
        return [
            'empty' => [
                SchemaResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                SchemaResponse::__set_state([
                    'Id' => 'Int',
                    'CategoryId' => [
                        1 => 'Module 1',
                        2 => 'Module 2',
                        3 => 'Module 3',
                    ],
                    'ContactId' => 'Int',
                    'BusinessId' => 'Int',
                    'UserId' => [
                        1 => 'User 1',
                        2 => 'User 2',
                        3 => 'User 3',
                    ],
                    'Name' => 'Text(512)',
                    'StatusUpdatedAt' => 'DateTime',
                    'Deleted' => 'Int',
                ]),
                [
                    'Id' => 'Int',
                    'CategoryId' => [
                        1 => 'Module 1',
                        2 => 'Module 2',
                        3 => 'Module 3',
                    ],
                    'ContactId' => 'Int',
                    'BusinessId' => 'Int',
                    'UserId' => [
                        1 => 'User 1',
                        2 => 'User 2',
                        3 => 'User 3',
                    ],
                    'Name' => 'Text(512)',
                    'StatusUpdatedAt' => 'DateTime',
                    'Deleted' => 'Int',
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
     * @dataProvider casesProjectSchema
     */
    public function testGetProjectSchema(
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
        $schemaEndpoint = new SchemaEndpoint($client, new NullLogger());
        $schemaEndpoint->setCredentials($this->clientOptions);

        $schema = $schemaEndpoint->getProjectSchema($projectId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($schema, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesPersonSchema()
    {
        return [
            'empty' => [
                SchemaResponse::__set_state([]),
                []
            ],
            'basic' => [
                SchemaResponse::__set_state([
                    'EmailType' => 'Text(1024)',
                    'PhoneType' => 'Text(1024)',
                    'Id' => 'Int',
                    'BusinessId' => 'Int',
                    'ParentId' => 'Int',
                    'FirstName' => 'Text(255)',
                    'LastName' => 'Text(255)',
                    'Email' => 'Text(128)',
                    'Phone' => 'Text(128)',
                    'Description' => 'Text(4096)',
                    'Deleted' => 'Boolean',
                ]),
                [
                    'EmailType' => 'Text(1024)',
                    'PhoneType' => 'Text(1024)',
                    'Id' => 'Int',
                    'BusinessId' => 'Int',
                    'ParentId' => 'Int',
                    'FirstName' => 'Text(255)',
                    'LastName' => 'Text(255)',
                    'Email' => 'Text(128)',
                    'Phone' => 'Text(128)',
                    'Description' => 'Text(4096)',
                    'Deleted' => 'Boolean',
                ]
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     *
     * @throws \Exception
     *
     * @dataProvider casesPersonSchema
     */
    public function testGetPersonSchema(
        $expected,
        array $responseBody
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $schemaEndpoint = new SchemaEndpoint($client, new NullLogger());
        $schemaEndpoint->setCredentials($this->clientOptions);

        $schema = $schemaEndpoint->getPersonSchema();

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($schema, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesBusinessSchema()
    {
        return [
            'empty' => [
                SchemaResponse::__set_state([]),
                []
            ],
            'basic' => [
                SchemaResponse::__set_state([
                    'EmailType' => 'Text(1024)',
                    'PhoneType' => 'Text(1024)',
                    'Id' => 'Int',
                    'ParentId' => 'Int',
                    'Name' => 'Text(1000)',
                    'Email' => 'Text(128)',
                    'Phone' => 'Text(128)',
                    'Description' => 'Text(4096)',
                    'Deleted' => 'Boolean',
                ]),
                [
                    'EmailType' => 'Text(1024)',
                    'PhoneType' => 'Text(1024)',
                    'Id' => 'Int',
                    'ParentId' => 'Int',
                    'Name' => 'Text(1000)',
                    'Email' => 'Text(128)',
                    'Phone' => 'Text(128)',
                    'Description' => 'Text(4096)',
                    'Deleted' => 'Boolean',
                ]
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     *
     * @throws \Exception
     *
     * @dataProvider casesBusinessSchema
     */
    public function testGetBusinessSchema(
        $expected,
        array $responseBody
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $schemaEndpoint = new SchemaEndpoint($client, new NullLogger());
        $schemaEndpoint->setCredentials($this->clientOptions);

        $schema = $schemaEndpoint->getBusinessSchema();

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($schema, JSON_PRETTY_PRINT)
        );
    }
}
