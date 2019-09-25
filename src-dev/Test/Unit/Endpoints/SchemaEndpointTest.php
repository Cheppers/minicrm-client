<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse;
use Cheppers\MiniCrm\Endpoints\SchemaEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Client;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\SchemaEndpoint
 */
class SchemaEndpointTest extends MiniCrmBaseTest
{

    public function casesPersonSchema()
    {
        $personData = [
            'EmailType' => 'Text(1024)',
            'PhoneType' => 'Text(1024)',
            'FirstName' => 'Text(255)',
            'LastName' => 'Text(255)',
            'Email' => 'Text(128)',
            'Phone' => 'Text(128)',
            'Description' => 'Text(4096)',
            'Deleted' => 'Boolean',
        ];

        return [
            'person' => [
                $personData,
                $personData,
            ],
        ];
    }

    /**
     * @dataProvider casesPersonSchema
     */
    public function testGetPersonSchema(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Schema/Person'
            ),
        ]);
        $logger = new NullLogger();
        $schema = new SchemaEndpoint($client, $logger);
        $schema->setCredentials($this->clientOptions);

        $person = $schema->getPersonSchema();

        if ($expected) {
            static::assertEquals(
                json_encode(SchemaResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($person, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($person);
        }
    }

    public function casesBusinessSchema()
    {
        $businessData = [
            'EmailType' => 'Text(1024)',
            'PhoneType' => 'Text(1024)',
            'Id' => 'Int',
            'ParentId' => 'Int',
            'Name' => 'Text(255)',
            'Email' => 'Text(128)',
            'Phone' => 'Text(128)',
            'Description' => 'Text(4096)',
            'Deleted' => 'Boolean',
        ];

        return [
            'business' => [
                $businessData,
                $businessData,
            ],
        ];
    }

    /**
     * @dataProvider casesBusinessSchema
     */
    public function testGetBusinessSchema(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Schema/Business'
            ),
        ]);
        $logger = new NullLogger();
        $schema = new SchemaEndpoint($client, $logger);
        $schema->setCredentials($this->clientOptions);

        $business = $schema->getBusinessSchema();

        if ($expected) {
            static::assertEquals(
                json_encode(SchemaResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($business, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($business);
        }
    }

    public function casesProjectSchema()
    {
        $projectData = [
            'Id' => 'Int',
            'CategoryId' => 'Array',
            'ContactId' => 'Int',
            'BusinessId' => 'Int',
            'UserId' => 'Array',
            'Name' => 'Text(512)',
            'StatusUpdatedAt' => 'DateTime',
            'Deleted' => 'Int',
        ];

        return [
            'project' => [
                $projectData,
                $projectData,
            ],
        ];
    }

    /**
     * @dataProvider casesProjectSchema
     */
    public function testGetProjectSchema(array $expected, array $response)
    {
        $client = new Client([
            'handler' => $this->createMiniCrmMock(
                $response,
                'GET',
                '/Api/R3/Schema/Project/1'
            ),
        ]);
        $logger = new NullLogger();
        $schema = new SchemaEndpoint($client, $logger);
        $schema->setCredentials($this->clientOptions);

        $project = $schema->getProjectSchema(1);

        if ($expected) {
            static::assertEquals(
                json_encode(SchemaResponse::__set_state($expected), JSON_PRETTY_PRINT),
                json_encode($project, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($project);
        }
    }
}
