<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Address;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class AddressRequest extends RequestBase
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $contactId;

    /**
     * @var string
     */
    public $name;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'id':
                    $data['Id'] = $this->id;
                    break;
                case 'contactId':
                    $data['ContactId'] = $this->contactId;
                    break;
                case 'name':
                    $data['Name'] = $this->name;
                    break;
            }
        }

        return $data;
    }
}
