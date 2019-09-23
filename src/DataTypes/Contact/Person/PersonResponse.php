<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact\Person;

use Cheppers\MiniCrm\DataTypes\Contact\ContactResponseBase;

class PersonResponse extends ContactResponseBase
{
    /**
     * {@inheritdoc}
     */
    public static function __set_state($data)
    {
        $instance = new static();

        foreach ($data as $key => $element) {
            $instance->{lcfirst($key)} = $element;
        }

        return $instance;
    }

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
}
