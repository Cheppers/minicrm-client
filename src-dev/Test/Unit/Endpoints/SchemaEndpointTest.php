<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Schema\SchemaResponse;
use Cheppers\MiniCrm\Endpoints\SchemaEndpoint;
use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\SchemaEndpoint
 */
class SchemaEndpointTest extends TestCase
{

    /**
     * @var MiniCrmClient
     */
    protected $client;

    protected $clientOptions = [
        'baseUri' => 'http://minicrm.hu',
        'apiKey' => 'm-i-n-i',
        'systemId' => 1234
    ];
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $client = new Client();
        $logger = new NullLogger();
        $this->client = new MiniCrmClient($client, $logger);
    }

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
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($response)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Schema/Person')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
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
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($response)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Schema/Business')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
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
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($response)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Schema/Project/1')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
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
