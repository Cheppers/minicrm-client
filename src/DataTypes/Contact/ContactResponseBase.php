<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class ContactResponseBase extends ResponseBase
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
    public $id;

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
}
