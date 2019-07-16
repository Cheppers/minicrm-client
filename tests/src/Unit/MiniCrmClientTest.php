<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit;

use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\MiniCrmClient
 */
class MiniCrmClientTest extends TestCase
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
    protected function setUp() : void
    {
        $client = new Client();
        $this->client = new MiniCrmClient($client);
    }

    public function testGetSetBaseUri()
    {
        static::assertEquals('', $this->client->getBaseUri());
        $this->client->setBaseUri('http://minicrm.hu');
        static::assertEquals('http://minicrm.hu', $this->client->getBaseUri());
    }

    public function testGetSetApiKey()
    {
        static::assertEquals('', $this->client->getApiKey());
        $this->client->setApiKey('m-i-n-i');
        static::assertEquals('m-i-n-i', $this->client->getApiKey());
    }

    public function testGetSetSystemId()
    {
        static::assertEquals('', $this->client->getSystemId());
        $this->client->setSystemId(1234);
        static::assertEquals(1234, $this->client->getSystemId());
    }

    public function testSetOptions()
    {
        $this->client->setOptions([
            'baseUri' => 'http://minicrm.hu',
            'apiKey' => 'm-i-n-i',
            'systemId' => 1234
        ]);
        static::assertEquals('http://minicrm.hu', $this->client->getBaseUri());
        static::assertEquals('m-i-n-i', $this->client->getApiKey());
        static::assertEquals(1234, $this->client->getSystemId());
    }

    public function casesCategories()
    {
        $data = [
            'category1',
            'category2',
            'category3',
        ];

        return [
            'basic' => [
                $data,
                ['Results' => $data],
            ],
        ];
    }

    /**
     * @dataProvider casesCategories
     */
    public function testCategoriesGet(array $expected, array $responseBody)
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Category')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $categories = (new MiniCrmClient($client))
            ->setOptions($this->clientOptions)
            ->getCategories()
            ->fetch();

        if ($expected) {
            static::assertEquals(
                json_encode($expected, JSON_PRETTY_PRINT),
                json_encode($categories['Results'], JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($categories);
        }

        /** @var Request $request */
        $request = $container[0]['request'];
        static::assertEquals(1, count($container));
        static::assertEquals('GET', $request->getMethod());
        static::assertEquals(['application/json'], $request->getHeader('Content-Type'));
        static::assertEquals(
            "{$this->clientOptions['baseUri']}/Api/R3/Category",
            (string) $request->getUri()
        );
    }

    public function casesCategoriesFail()
    {
        return [
            'unauthorized' => [
                [
                    'class' => \Exception::class,
                    'code' => 401,
                    'msg' => '401 Unauthorized',
                ],
                [
                    'code' => 401,
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => '401 Unauthorized',
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesCategoriesFail
     */
    public function testCategoriesFail(array $expected, array $response)
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(
                $response['code'],
                $response['headers'],
                \GuzzleHttp\json_encode($response['body'])
            ),
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', '/Api/R3/Category')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $categories = (new MiniCrmClient($client))
            ->setOptions($this->clientOptions);

        static::expectException($expected['class']);
        static::expectExceptionCode($expected['code']);
        static::expectExceptionMessage($expected['msg']);

        $categories->getCategories()->fetch();
    }
}
