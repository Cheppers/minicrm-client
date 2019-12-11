<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\Endpoints;

use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessRequest;
use Cheppers\MiniCrm\DataTypes\Contact\Business\BusinessResponse;
use Cheppers\MiniCrm\Endpoints\BusinessEndpoint;
use Cheppers\MiniCrm\Test\Unit\MiniCrmBaseTest;
use GuzzleHttp\Psr7\Response;
use Psr\Log\NullLogger;

/**
 * @group Endpoints
 *
 * @covers \Cheppers\MiniCrm\Endpoints\BusinessEndpoint
 */
class BusinessEndpointTest extends MiniCrmBaseTest
{
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
     * @param int $contactId
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
        $businessEndpoint = new BusinessEndpoint($client, new NullLogger());
        $businessEndpoint->setCredentials($this->clientOptions);

        $contact = $businessEndpoint->get($contactId);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesBusinessCreate()
    {
        return [
            'empty' => [
                [],
                [],
                BusinessRequest::__set_state([])
            ],
            'basic' => [
                [
                    'Id' => 42,
                ],
                [
                    'Id' => 42,
                ],
                BusinessRequest::__set_state([
                    'Email' => 'test@em.ail',
                    'EmailType' => 'Test Email Type',
                    'Phone' => '123456789',
                    'PhoneType' => 'Test Phone Type',
                    'Name' => 'Test Business Name',
                    'Description' => 'Test Description',
                    'Url' => 'http://test.url',
                    'Industry' => 'Test Industry',
                    'Region' => 'Test Region',
                    'VatNumber' => 'Test Vat Number',
                    'RegistrationNumber' => 'Test Registration Number',
                    'BankAccount' => 'Test Bank Account Number',
                    'Swift' => 'Test Swift Number',
                    'Employees' => 99,
                    'YearlyRevenue' => 42,
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
     * @dataProvider casesBusinessCreate
     */
    public function testCreateBusiness(
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
        $businessEndpoint = new BusinessEndpoint($client, new NullLogger());
        $businessEndpoint->setCredentials($this->clientOptions);

        $contact = $businessEndpoint->create($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @return array
     */
    public function casesUpdateBusiness()
    {
        return [
            'basic' => [
                [
                    'Id' => 299,
                ],
                [
                    'Id' => 299,
                ],
                BusinessRequest::__set_state([
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
     * @dataProvider casesUpdateBusiness
     */
    public function testUpdateBusiness(
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
        $businessEndpoint = new BusinessEndpoint($client, new NullLogger());
        $businessEndpoint->setCredentials($this->clientOptions);

        $contact = $businessEndpoint->update($request);

        static::assertEquals(
            json_encode($expected, JSON_PRETTY_PRINT),
            json_encode($contact, JSON_PRETTY_PRINT)
        );
    }
}
