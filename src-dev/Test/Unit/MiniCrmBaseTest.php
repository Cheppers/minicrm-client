<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit;

use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;

/**
 * @group MiniCrmClient
 */
abstract class MiniCrmBaseTest extends TestCase
{
    /**
     * @var MiniCrmClient
     */
    public $client;

    /**
     * @var array
     */
    public $clientOptions = [
        'baseUri' => 'http://minicrm.hu',
        'apiKey' => 'm-i-n-i',
        'systemId' => 1234
    ];

    /**
     * @param string $baseUri
     * @param string $apiKey
     * @param int $systemId
     */
    public function setClientOptions(
        string $baseUri,
        string $apiKey,
        int $systemId
    ) {
        $this->clientOptions = [
            'baseUri' => $baseUri,
            'apiKey' => $apiKey,
            'systemId' => $systemId,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $client = new Client();
        $logger = new NullLogger();
        $this->client = new MiniCrmClient($client, $logger);
    }

    /**
     * @param $response
     * @param $method
     * @param $path
     *
     * @return \GuzzleHttp\HandlerStack
     */
    protected function createMiniCrmMock($response, $method, $path)
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
                'Error communicating with the server.',
                new Request($method, $path)
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        return $handlerStack;
    }
}
