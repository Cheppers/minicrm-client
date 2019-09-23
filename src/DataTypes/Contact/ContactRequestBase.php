<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class ContactRequestBase extends RequestBase
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $emailType;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $phoneType;

    /**
     * @var string
     */
    public $description;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'type':
                    $data['Type'] = $this->type;
                    break;
                case 'email':
                    $data['Email'] = $this->email;
                    break;
                case 'emailType':
                    $data['EmailType'] = $this->emailType;
                    break;
                case 'phone':
                    $data['Phone'] = $this->phone;
                    break;
                case 'phoneType':
                    $data['PhoneType'] = $this->phoneType;
                    break;
                case 'description':
                    $data['Description'] = $this->description;
                    break;
            }
        }

        return $data;
    }
}
