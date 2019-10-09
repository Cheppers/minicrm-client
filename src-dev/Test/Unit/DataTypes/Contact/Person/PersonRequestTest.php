<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Contact\Person;

use Cheppers\MiniCrm\Datatypes\Contact\Person\PersonRequest;
use PHPUnit\Framework\TestCase;

class PersonRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Id' => 42,
                    'Type' => 'Személy',
                    'Email' => 'test@ema.il',
                    'EmailType' => 'Home',
                    'Phone' => '123456789',
                    'PhoneType' => 'Home',
                    'Description' => 'John Doe is the leader',
                    'BusinessId' => 1,
                    'FirstName' => 'John',
                    'LastName' => 'Doe',
                    'Position' => 'Leader',
                ],
                [
                    'id' => 42,
                    'type' => 'Személy',
                    'email' => 'test@ema.il',
                    'emailType' => 'Home',
                    'phone' => '123456789',
                    'phoneType' => 'Home',
                    'description' => 'John Doe is the leader',
                    'businessId' => 1,
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'position' => 'Leader',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $personRequest = new PersonRequest();
        $personRequest->id = $data['id'];
        $personRequest->type = $data['type'];
        $personRequest->email = $data['email'];
        $personRequest->emailType = $data['emailType'];
        $personRequest->phone = $data['phone'];
        $personRequest->phoneType = $data['phoneType'];
        $personRequest->description = $data['description'];
        $personRequest->firstName = $data['firstName'];
        $personRequest->lastName = $data['lastName'];
        $personRequest->businessId = $data['businessId'];
        $personRequest->position = $data['position'];

        static::assertSame($expected, $personRequest->jsonSerialize());
    }
}
