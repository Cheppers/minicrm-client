<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\Test\Unit\DataTypes\Address;

use Cheppers\MiniCrm\Datatypes\Address\AddressRequest;
use PHPUnit\Framework\TestCase;

class AddressRequestTest extends TestCase
{
    public function casesJsonSerialize()
    {
        return [
            'basic' => [
                [
                    'Id' => 1,
                    'ContactId' => 42,
                    'Name' => 'Cheppers',
                ],
                [
                    'id' => 1,
                    'contactId' => 42,
                    'name' => 'Cheppers',
                ]
            ],
        ];
    }

    /**
     * @dataProvider casesJsonSerialize
     */
    public function testJsonSerialize($expected, $data)
    {
        $addressRequest = new AddressRequest();
        $addressRequest->id = $data['id'];
        $addressRequest->name = $data['name'];
        $addressRequest->contactId = $data['contactId'];

        static::assertSame($expected, $addressRequest->jsonSerialize());
    }
}
