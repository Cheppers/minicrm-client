<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonRequest;
use Cheppers\MiniCrm\DataTypes\Contact\Person\PersonResponse;
use Cheppers\MiniCrm\Endpoints\ContactEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group MiniCrmClient
 *
 * @covers \Cheppers\MiniCrm\Endpoints\ContactEndpoint
 */
class ContactEndpointTest extends MiniCrmBaseTest
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
     * @param $contactId
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
        $contactEndpoint = new ContactEndpoint($client, new NullLogger());
        $contactEndpoint->setCredentials($this->clientOptions);

        $contact = $contactEndpoint->getPerson($contactId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesBusiness()
    {
        return [
            'empty' => [
                BusinessResponse::__set_state([]),
                [],
                1
            ],
            'basic' => [
                BusinessResponse::__set_state([
                    'Name' => 'Test Name',
                    'Description' => 'Test Contact Description',
                    'Url' => '',
                    'Industry' => null,
                    'Region' => 'Test Region',
                    'VatNumber' => 'Test Vat Number',
                    'RegistrationNumber' => 'Test Registration Number',
                    'BankAccount' => 'Test Bank Account Number',
                    'Swift' => 'Test Swift Number',
                    'Employees' => 99,
                    'YearlyRevenue' => 0,
                    'Id' => 42,
                    'Type' => 'Test Contact Type',
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'ParentId' => 0,
                    'Deleted' => 0,
                ]),
                [
                    'Name' => 'Test Name',
                    'Description' => 'Test Contact Description',
                    'Url' => '',
                    'Industry' => null,
                    'Region' => 'Test Region',
                    'VatNumber' => 'Test Vat Number',
                    'RegistrationNumber' => 'Test Registration Number',
                    'BankAccount' => 'Test Bank Account Number',
                    'Swift' => 'Test Swift Number',
                    'Employees' => 99,
                    'YearlyRevenue' => 0,
                    'Id' => 42,
                    'Type' => 'Test Contact Type',
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'ParentId' => 0,
                    'Deleted' => 0,
                ],
                42
            ],
        ];
    }

    /**
     * @param $expected
     * @param array $responseBody
     * @param $contactId
     *
     * @throws \Exception
     *
     * @dataProvider casesBusiness
     */
    public function testGetBusiness(
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
        $contactEndpoint = new ContactEndpoint($client, new NullLogger());
        $contactEndpoint->setCredentials($this->clientOptions);

        $contact = $contactEndpoint->getBusiness($contactId);

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
                    'Email' => 'szem@el.y1',
                    'EmailType' => 'teszt email type',
                    'Phone' => '+363123141',
                    'PhoneType' => 'teszt phone type',
                    'FirstName' => 'személy2',
                    'LastName' => 'személy1last',
                    'Position' => 'Értékesítés',
                    'Description' => 'teszt adat',
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
        $contactEndpoint = new ContactEndpoint($client, new NullLogger());
        $contactEndpoint->setCredentials($this->clientOptions);

        $contact = $contactEndpoint->createPerson($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }
}
