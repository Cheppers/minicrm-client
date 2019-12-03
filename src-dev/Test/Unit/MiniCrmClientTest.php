<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit;

use Cheppers\MiniCrm\DataTypes\RequestBase;
use Cheppers\MiniCrm\DataTypes\Schema\SchemaRequest;
use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\MiniCrmClient
 */
class MiniCrmClientTest extends MiniCrmBaseTest
{
    /**
     * @var MiniCrmClient
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $client = new Client();
        $logger = new NullLogger();
        $this->client = new MiniCrmClient($client, $logger);
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
        $this->client->setSystemId('1234');
        static::assertEquals('1234', $this->client->getSystemId());
    }

    public function casesCredentials()
    {
        return [
            'credential-1' => [
                [
                    'systemid' => '12345',
                    'apikey' => '3EiTVRS9WCHuqjZm6c0Ov8nROxGtW7LL',
                    'baseUri' => 'testuri',
                ],
                'MTIzNDU6M0VpVFZSUzlXQ0h1cWpabTZjME92OG5ST3hHdFc3TEw=',
            ],
            'credential-2' => [
                [
                    'systemid' => '98765',
                    'apikey' => '9Lsc3OrDqBeehFCtfRiDhtay881YVCft',
                    'baseUri' => 'nottesturi',
                ],
                'OTg3NjU6OUxzYzNPckRxQmVlaEZDdGZSaURodGF5ODgxWVZDZnQ=',
            ],
        ];
    }

    /**
     * @param array $credentials
     * @param string $expected
     *
     * @dataProvider casesCredentials
     */
    public function testGetCredentials(array $credentials, string $expected)
    {
        $this->client->setSystemId($credentials['systemid']);
        $this->client->setApiKey($credentials['apikey']);
        static::assertSame($expected, base64_encode($this->client->getSystemId().':'.$this->client->getApiKey()));
    }

    public function testSetCredentials()
    {
        $this->client->setCredentials([
            'baseUri' => 'http://minicrm.hu',
            'apiKey' => 'm-i-n-i',
            'systemId' => '1234',
            'default' => 1
        ]);
        static::assertEquals('http://minicrm.hu', $this->client->getBaseUri());
        static::assertEquals('m-i-n-i', $this->client->getApiKey());
        static::assertEquals('1234', $this->client->getSystemId());
    }

    public function casesSendRequest()
    {
        return [
            'basic' => [
                new Response(
                    200,
                    ['Content-Type' => 'application/json; charset=utf-8'],
                    \GuzzleHttp\json_encode([0 => 'OK'])
                ),
                [
                    0 => 'OK',
                ],
                [
                    'method' => 'GET',
                    'request' => SchemaRequest::__set_state([]),
                    'path' => '/test',
                ],
            ],
        ];
    }

    /**
     * @param $expected
     * @param $responseBody
     * @param $parameters
     *
     * @dataProvider casesSendRequest
     */
    public function testSendRequest(
        $expected,
        $responseBody,
        $parameters
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $miniCrmClient = new MiniCrmClient($client, new NullLogger());
        $miniCrmClient->setCredentials($this->clientOptions);

        $result = $miniCrmClient->sendRequest(
            $parameters['method'],
            $parameters['request'],
            $parameters['path']
        );

        static::assertSame(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($result, JSON_PRETTY_PRINT)
        );
    }
}
