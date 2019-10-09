<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Contact\Business;

use Cheppers\MiniCrm\Datatypes\Contact\Business\BusinessRequest;
use PHPUnit\Framework\TestCase;

class BusinessRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Id' => 42,
                    'Type' => 'Cég',
                    'Email' => 'test@ema.il',
                    'EmailType' => 'Support',
                    'Phone' => '123456789',
                    'PhoneType' => 'BusinessPhone',
                    'Description' => 'Cheppers is the best',
                    'Name' => 'Cheppers',
                    'Url' => 'http://cheppers.com',
                    'Industry' => 'Web',
                    'Region' => 'EU',
                    'VatNumber' => '1234',
                    'RegistrationNumber' => '1234',
                    'BankAccount' => '1234',
                    'Swift' => '1234',
                    'Employees' => 60,
                    'YearlyRevenue' => 9999999,
                    'FoundingYear' => '2012',
                    'MainActivity' => 'Drupal',
                ],
                [
                    'id' => 42,
                    'type' => 'Cég',
                    'email' => 'test@ema.il',
                    'emailType' => 'Support',
                    'phone' => '123456789',
                    'phoneType' => 'BusinessPhone',
                    'description' => 'Cheppers is the best',
                    'name' => 'Cheppers',
                    'url' => 'http://cheppers.com',
                    'industry' => 'Web',
                    'region' => 'EU',
                    'vatNumber' => '1234',
                    'registrationNumber' => '1234',
                    'bankAccount' => '1234',
                    'swift' => '1234',
                    'employees' => 60,
                    'yearlyRevenue' => 9999999,
                    'foundingYear' => '2012',
                    'mainActivity' => 'Drupal',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $businessRequest = new BusinessRequest();
        $businessRequest->id = $data['id'];
        $businessRequest->type = $data['type'];
        $businessRequest->email = $data['email'];
        $businessRequest->emailType = $data['emailType'];
        $businessRequest->phone = $data['phone'];
        $businessRequest->phoneType = $data['phoneType'];
        $businessRequest->description = $data['description'];
        $businessRequest->name = $data['name'];
        $businessRequest->url = $data['url'];
        $businessRequest->industry = $data['industry'];
        $businessRequest->region = $data['region'];
        $businessRequest->vatNumber = $data['vatNumber'];
        $businessRequest->registrationNumber = $data['registrationNumber'];
        $businessRequest->bankAccount = $data['bankAccount'];
        $businessRequest->swift = $data['swift'];
        $businessRequest->employees = $data['employees'];
        $businessRequest->yearlyRevenue = $data['yearlyRevenue'];
        $businessRequest->foundingYear = $data['foundingYear'];
        $businessRequest->mainActivity = $data['mainActivity'];

        static::assertSame($expected, $businessRequest->jsonSerialize());
    }
}
