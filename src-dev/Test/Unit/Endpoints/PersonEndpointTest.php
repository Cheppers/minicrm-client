<?php

declare(strict_types=1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonRequest;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonResponse;
use Cheppers\MiniCrm\Endpoints\PersonEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\PersonEndpoint
 */
class PersonEndpointTest extends MiniCrmBaseTest
{
    /**
     * @return array
     */
    public function casesPerson()
    {
        return [
            'empty' => [
                PersonResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                PersonResponse::__set_state([
                    'BusinessId' => null,
                    'FirstName' => 'Test First Name',
                    'LastName' => 'Test Last Name',
                    'Position' => 'Test Position',
                    'Id' => 42,
                    'Type' => 'Test Contact Type',
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'ParentId' => 0,
                    'Description' => 'Test Contact Description',
                    'Deleted' => 0,
                    'Url' => '',
                    'BankAccount' => 'Test Bank Account Number',
                    'Swift' => 'Test Swift Number',
                    'VatNumber' => 'Test Vat Number',
                    'Industry' => null,
                    'Region' => 'Test Region',
                    'YearlyRevenue' => 0,
                ]),
                [
                    'BusinessId' => null,
                    'FirstName' => 'Test First Name',
                    'LastName' => 'Test Last Name',
                    'Position' => 'Test Position',
                    'Id' => 42,
                    'Type' => 'Test Contact Type',
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'ParentId' => 0,
                    'Description' => 'Test Contact Description',
                    'Deleted' => 0,
                    'Url' => '',
                    'BankAccount' => 'Test Bank Account Number',
                    'Swift' => 'Test Swift Number',
                    'VatNumber' => 'Test Vat Number',
                    'Industry' => null,
                    'Region' => 'Test Region',
                    'YearlyRevenue' => 0,
                ],
                42
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $contactId
     *
     * @throws \Exception
     *
     * @dataProvider casesPerson
     */
    public function testGetPerson(
        $expected,
        array $responseBody,
        int $contactId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $personEndpoint = new PersonEndpoint($client, new NullLogger());
        $personEndpoint->setCredentials($this->clientOptions);

        $contact = $personEndpoint->get($contactId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesPersonCreate()
    {
        return [
            'empty' => [
                [],
                [],
                PersonRequest::__set_state([])
            ],
            'basic' => [
                [
                    'Id' => 42,
                ],
                [
                    'Id' => 42,
                ],
                PersonRequest::__set_state([
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'FirstName' => 'Test First Name',
                    'LastName' => 'Test Last Name',
                    'Position' => 'Test Position',
                    'Description' => 'Test Description',
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
     * @dataProvider casesPersonCreate
     */
    public function testCreatePerson(
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
        $personEndpoint = new PersonEndpoint($client, new NullLogger());
        $personEndpoint->setCredentials($this->clientOptions);

        $contact = $personEndpoint->create($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesUpdatePerson()
    {
        return [
            'basic' => [
                [
                    'Id' => 299,
                ],
                [
                    'Id' => 299,
                ],
                PersonRequest::__set_state([
                    'id' => 299,
                    'firstName' => 'Test First Name Updated',
                ]),
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
     * @dataProvider casesUpdatePerson
     */
    public function testUpdatePerson(
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
            // Mocking response of inner method getPerson().
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $personEndpoint = new PersonEndpoint($client, new NullLogger());
        $personEndpoint->setCredentials($this->clientOptions);

        $contact = $personEndpoint->update($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesDeletePerson()
    {
        return [
            'basic' => [
                [
                    0 => 'Successful deletion.'
                ],
                [
                    0 => 'Successful deletion.',
                ],
                42
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param int $personId
     *
     * @throws \Exception
     *
     * @dataProvider casesDeletePerson
     */
    public function testDeletePerson(
        $expected,
        array $responseBody,
        int $personId
    ) {
        $mock = $this->createMiniCrmMock([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                \GuzzleHttp\json_encode($responseBody)
            ),
        ]);
        $client = $mock['client'];
        $personEndpoint = new PersonEndpoint($client, new NullLogger());
        $personEndpoint->setCredentials($this->clientOptions);

        $contact = $personEndpoint->delete($personId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }
}
