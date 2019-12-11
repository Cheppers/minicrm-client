<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * @group MiniCrmClient
 */
abstract class MiniCrmBaseTest extends TestCase
{
    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->clientOptions = [
            'baseUri' => 'http://minicrm.hu',
            'apiKey' => 'm-i-n-i',
            'systemId' => '1234',
        ];
    }

    /**
     * @param array $requests
     *
     * @return array
     */
    public function createMiniCrmMock(array $requests)
    {
        $requests[] = new RequestException(
            'Error Communicating with Server',
            new Request('GET', 'unexpected_request')
        );
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler($requests);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $client = new Client(['handler' => $handlerStack]);

        return [
            'client' => $client,
            'container' => &$container,
            'history' => $history,
            'handlerStack' => $handlerStack,
            'mock' => $mock,
        ];
    }


    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $this->clientOptions = [];
    }
}
