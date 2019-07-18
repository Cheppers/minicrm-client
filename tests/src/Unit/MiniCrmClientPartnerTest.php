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
class MiniCrmClientPartnerTest extends TestCase
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

    public function casesPartner()
    {
        $basicData = [
            'Name' => 'MiniCRM',
            'ProjectID' => 1234,
            'BusinessID' => 9876,
            'Page' => null,
        ];
        $withoutProjectIdData = array_replace($basicData, ['ProjectID' => null]);
        $withoutBusinessIdData = array_replace($basicData, ['BusinessID' => null]);
        $withEmptyNameData = array_replace($basicData, ['Name' => '']);
        $withPageData = array_replace($basicData, ['Page' => 1]);

        return [
            'basic' => [
                $basicData,
                ['Results' => $basicData],
                ['Results' => [
                        '1234' => [
                            'id' => 1234
                        ],
                    ],
                ]
            ],
            'withoutProjectId' => [
                $withoutProjectIdData,
                ['Results' => $withoutProjectIdData],
                ['Results' => [
                        '1234' => [
                            'id' => 1234
                        ],
                    ],
                ]
            ],
            'withoutBusinessId' => [
                $withoutBusinessIdData,
                ['Results' => $withoutBusinessIdData],
                ['Results' => [
                        '1234' => [
                            'id' => 1234
                        ],
                    ],
                ]
            ],

            'withEmptyName' => [
                $withEmptyNameData,
                ['Results' => $withEmptyNameData],
                ['Results' => [
                        '1234' => [
                            'id' => 1234
                        ],
                    ],
                ]
            ],

            'withPage' => [
                $withPageData,
                ['Results' => $withPageData],
                ['Results' => [
                        '1234' => [
                            'id' => 1234
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesPartner
     */
    public function testCategoriesGet(array $expected, array $partnerResponseBody, array $partnerResponseId)
    {
        $container = [];
        $history = Middleware::history($container);
        $response = [
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($partnerResponseBody)
            ),
            new RequestException(
                'Error communicating with server.',
                new Request('GET', '/Api/R3/Category')
            )
        ];

        // If name is empty, client won't call getPartnerId(), so need to add Response if Name has been provided.
        if ($expected['Name'] !== '') {
            array_unshift(
                $response,
                new Response(
                    200,
                    ['Content-Type' => 'application/json; charset=utf-8'],
                    \GuzzleHttp\json_encode($partnerResponseId)
                )
            );
        }

        $mock = new MockHandler($response);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client([
            'handler' => $handlerStack,
        ]);

        $categories = (new MiniCrmClient($client))
            ->setOptions($this->clientOptions)
            ->getPartner($expected['Name'], $expected['ProjectID'], $expected['BusinessID'], $expected['Page'])
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
}
