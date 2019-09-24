<?php
declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Category\CategoryRequest;
use Cheppers\MiniCrm\Endpoints\CategoryEndpoint;
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
 * @covers \Cheppers\MiniCrm\Endpoints\CategoryEndpoint
 */
class CategoryEndpointTest extends TestCase
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

    public function casesCategories()
    {
        $categoryData = [
            '1' => 'Test Category 1',
            '2' => 'Test Category 2',
            '3' => 'Test Category 3',
            '4' => 'Test Category 4',
            '5' => 'Test Category 5',
        ];

        return [
            'category' => [
                $categoryData,
                $categoryData,
            ],
        ];
    }

    /**
     * @dataProvider casesCategories
     */
    public function testGetCategories(array $expected, array $response)
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
        $category = new CategoryEndpoint($client, $logger);
        $category->setCredentials($this->clientOptions);

        $body = CategoryRequest::__set_state($expected);
        $categories = $category->getCategories($body);

        if ($expected) {
            static::assertEquals(
                json_encode($expected, JSON_PRETTY_PRINT),
                json_encode($categories->results, JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($categories);
        }
    }
}
