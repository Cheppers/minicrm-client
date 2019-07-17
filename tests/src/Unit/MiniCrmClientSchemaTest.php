<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit;

use Cheppers\MiniCrm\MiniCrmClient;
use Cheppers\MiniCrm\MiniCrmClientException;
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
class MiniCrmClientSchemaTest extends TestCase
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
        $this->client = new MiniCrmClient($client);
    }

    public function casesSchema()
    {
        $data = [
            "EmailType" => "Text(1024)",
            "PhoneType" => "Text(1024)",
        ];

        return [
            'basic' => [
                $data,
                ['Results' => $data],
            ],
        ];
    }

    /**
     * @dataProvider casesSchema
     */
    public function testGetSchema(array $expected, array $responseBody)
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
                new Request('GET', '/Api/R3/Schema/Business')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $categories = (new MiniCrmClient($client))
            ->setOptions($this->clientOptions)
            ->getSchema('Business')
            ->fetch();

        if ($expected) {
            static::assertEquals(
                json_encode($expected, JSON_PRETTY_PRINT),
                json_encode($categories['Results'], JSON_PRETTY_PRINT)
            );
        } else {
            static::assertNull($categories);
        }
    }

    public function casesGetSchemaFail()
    {
        $msg = 'The data (type) you provided is invalid. Please use either "Business", "Person" or "Project/$ID"';

        return [
            'unauthorized' => [
                [
                    'class' => MiniCrmClientException::class,
                    'code' => 6,
                    'msg' => $msg,
                ],
                [
                    'code' => 401,
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => $msg,
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesGetSchemaFail
     */
    public function testGetSchemaFail(array $expected, array $response)
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
                new Request('GET', '/Api/R3/Schema/Business')
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $client = (new MiniCrmClient($client))
            ->setOptions($this->clientOptions);

        static::expectException($expected['class']);
        static::expectExceptionCode($expected['code']);
        static::expectExceptionMessage($expected['msg']);

        $client->getSchema('invalidSchemaName')->fetch();
    }
}
