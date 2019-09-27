<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

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
    public function casesTodo()
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
            'only-status' => [
                PersonResponse::__set_state([
                    'Status' => 'Open',
                ]),
                [
                    'Status' => 'Open',
                ],
                13
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
     * @dataProvider casesTodo
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
}
