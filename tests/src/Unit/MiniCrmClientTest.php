<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Tests\Unit;

use Cheppers\MiniCrm\MiniCrmClient;
use GuzzleHttp\Client;
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


    public function casesCredentials()
    {
        return [
            'credential-1' => [
                [
                    'systemid' => 'systemid',
                    'apikey' => 'apikey',
                ],
                'c3lzdGVtaWQ6YXBpa2V5',
            ],
            'credential-2' => [
                [
                    'systemid' => '12345',
                    'apikey' => '3EiTVRS9WCHuqjZm6c0Ov8nROxGtW7LL',
                ],
                'MTIzNDU6M0VpVFZSUzlXQ0h1cWpabTZjME92OG5ST3hHdFc3TEw=',
            ],
            'credential-3' => [
                [
                    'systemid' => '98765',
                    'apikey' => '9Lsc3OrDqBeehFCtfRiDhtay881YVCft',
                ],
                'OTg3NjU6OUxzYzNPckRxQmVlaEZDdGZSaURodGF5ODgxWVZDZnQ=',
            ],
        ];
    }

    /**
     * @dataProvider casesCredentials
     */
    public function testGetCredentials(array $credentials, string $expected)
    {
        $this->client->setSystemId($credentials['systemid']);
        $this->client->setApiKey($credentials['apikey']);

        static::assertSame($expected, $this->client->getCredentials());
    }
}
