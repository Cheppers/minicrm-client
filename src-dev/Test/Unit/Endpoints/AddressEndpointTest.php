<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Address\AddressRequest;
use Cheppers\MiniCrm\DataTypes\Address\AddressResponse;
use Cheppers\MiniCrm\DataTypes\Address\StructuredAddressItem;
use Cheppers\MiniCrm\DataTypes\Address\StructuredAddressResponse;
use Cheppers\MiniCrm\Endpoints\AddressEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\AddressEndpoint
 */
class AddressEndpointTest extends MiniCrmBaseTest
{

    /**
     * @return array
     */
    public function casesAddress()
    {
        return [
            'empty' => [
                AddressResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                AddressResponse::__set_state([
                    'ContactId' => 42,
                    'Id' => 42,
                    'Type' => 'Test Address Type',
                    'Name' => 'Test Address Name',
                    'CountryId' => 'Test Country',
                    'PostalCode' => 9999,
                    'City' => 'Test City',
                    'County' => 'Test County',
                    'Address' => 'Test Address',
                    'Default' => 1,
                ]),
                [
                    'ContactId' => 42,
                    'Id' => 42,
                    'Type' => 'Test Address Type',
                    'Name' => 'Test Address Name',
                    'CountryId' => 'Test Country',
                    'PostalCode' => 9999,
                    'City' => 'Test City',
                    'County' => 'Test County',
                    'Address' => 'Test Address',
                    'Default' => 1,
                ],
                42
            ],
            'only-name' => [
                AddressResponse::__set_state([
                    'Name' => 'Test Address Name',
                ]),
                [
                    'Name' => 'Test Address Name',
                ],
                13
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $addressId
     *
     * @throws \Exception
     *
     * @dataProvider casesAddress
     */
    public function testGetAddress(
        $expected,
        array $responseBody,
        int $addressId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $addressEndpoint = new AddressEndpoint($client, new NullLogger());
        $addressEndpoint->setCredentials($this->clientOptions);

        $address = $addressEndpoint->getAddress($addressId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($address, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesAddresses()
    {
        return [
            'empty' => [
                AddressResponse::__set_state([]),
                [],
                AddressRequest::__set_state([]),
            ],
            'basic' => [
                AddressResponse::__set_state([
                    'Results' => [
                        0 => 'Test Address 1',
                        1 => 'Test Address 2',
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => 'Test Address 1',
                        1 => 'Test Address 2',
                    ],
                    'Count' => 1
                ],
                AddressRequest::__set_state([
                    'contactId' => 19,
                ])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesAddresses
     */
    public function testGetAddresses(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $addressEndpoint = new AddressEndpoint($client, new NullLogger());
        $addressEndpoint->setCredentials($this->clientOptions);

        $address = $addressEndpoint->getAddresses($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($address, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesAddressesStructured()
    {
        return [
            'empty' => [
                StructuredAddressResponse::__set_state([
                    'Results' => null,
                    'Count' => null,
                ]),
                [
                    'Results' => null,
                    'Count' => null,
                ],
                AddressRequest::__set_state([]),
            ],
            'basic' => [
                StructuredAddressResponse::__set_state([
                    'Results' => [
                        0 => StructuredAddressItem::__set_state([
                            'Id' => 42,
                            'Url' => 'Test Item Url',
                            'Address' => 'Test Item Address',
                        ])
                    ],
                    'Count' => 1
                ]),
                [
                    'Results' => [
                        0 => [
                            'Id' => 42,
                            'Url' => 'Test Item Url',
                            'Address' => 'Test Item Address',
                        ]
                    ],
                    'Count' => 1
                ],
                AddressRequest::__set_state([
                    'contactId' => 19,
                ])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesAddressesStructured
     */
    public function testGetStructuredAddresses(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $addressEndpoint = new AddressEndpoint($client, new NullLogger());
        $addressEndpoint->setCredentials($this->clientOptions);

        $address = $addressEndpoint->getAddresses($request, true);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($address, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesAddressCreate()
    {
        return [
            'empty' => [
                [],
                [],
                AddressRequest::__set_state([])
            ],
            'basic' => [
                [
                    'Id' => 42,
                ],
                [
                    'Id' => 42,
                ],
                AddressRequest::__set_state([
                    'contactId' => 2546,
                    'type' => 'Székhely',
                    'name' => 'tesztcím',
                    'countryId' => 'Magyarország',
                    'postalCode' => 2400,
                    'city' => 'Dunaújváros',
                    'county' => 'Fejér',
                    'address' => 'newaddresstest',
                    'default' => 0
                ])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesAddressCreate
     */
    public function testCreateAddress(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $addressEndpoint = new AddressEndpoint($client, new NullLogger());
        $addressEndpoint->setCredentials($this->clientOptions);

        $address = $addressEndpoint->createAddress($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($address, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesAddressUpdate()
    {
        return [
            'basic' => [
                [
                    'Id' => 42,
                ],
                [
                    'Id' => 42,
                ],
                AddressRequest::__set_state([
                    'id' => 42,
                    'name' => 'Test Name Updated',
                ])
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $request
     *
     * @throws \Exception
     *
     * @dataProvider casesAddressUpdate
     */
    public function testUpdateAddress(
        $expected,
        array $responseBody,
        $request
    ) {
        $mock = $this->createMiniCrmMock([
            // Mocking response of inner method getAddress().
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode(AddressResponse::__set_state([
                    'id' => $request->id,
                    'contactId' => 9999,
                ]))
            ),
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $addressEndpoint = new AddressEndpoint($client, new NullLogger());
        $addressEndpoint->setCredentials($this->clientOptions);

        $address = $addressEndpoint->updateAddress($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($address, JSON_PRETTY_PRINT)
        );
    }
}
