<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

class PersonRequest extends ContactRequestBase
{
    /**
     * @var int
     */
    public $businessId;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $position;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'type':
                    $data['Type'] = 'Személy';
                    break;
                case 'firstName':
                    $data['FirstName'] = $this->firstName;
                    break;
                case 'lastName':
                    $data['LastName'] = $this->lastName;
                    break;
                case 'businessId':
                    $data['BusinessId'] = $this->businessId;
                    break;
                case 'position':
                    $data['Position'] = $this->position;
                    break;
            }
        }

        return $data;
    }
}
