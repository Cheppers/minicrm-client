<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

class BusinessResponse extends ContactResponseBase
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
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $industry;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $vatNumber;

    /**
     * @var string
     */
    public $registrationNumber;

    /**
     * @var string
     */
    public $bankAccount;

    /**
     * @var string
     */
    public $swift;

    /**
     * @var int
     */
    public $employees;

    /**
     * @var int
     */
    public $yearlyRevenue;
}
