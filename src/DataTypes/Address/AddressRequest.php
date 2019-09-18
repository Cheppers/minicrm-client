<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Address;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class AddressRequest extends RequestBase
{
    /**
     * @var int
     */
    public $contactId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $countryId;

    /**
     * @var int
     */
    public $postalCode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $county;

    /**
     * @var string
     */
    public $address;

    /**
     * @var int
     */
    public $default;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'contactId':
                    $data['ContactId'] = $this->contactId;
                    break;
                case 'type':
                    $data['Type'] = $this->type;
                    break;
                case 'name':
                    $data['Name'] = $this->name;
                    break;
                case 'countryId':
                    $data['CountryId'] = $this->countryId;
                    break;
                case 'postalCode':
                    $data['PostalCode'] = $this->postalCode;
                    break;
                case 'city':
                    $data['City'] = $this->city;
                    break;
                case 'county':
                    $data['County'] = $this->county;
                    break;
                case 'address':
                    $data['Address'] = $this->address;
                    break;
                case 'default':
                    $data['Default'] = $this->default;
                    break;
            }
        }

        return $data;
    }
}
