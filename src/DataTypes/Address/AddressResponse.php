<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Address;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class AddressResponse extends ResponseBase
{
    public static function __set_state($data)
    {
        $instance = new static();

        foreach ($data as $key => $element) {
            $instance->{$key} = $element;
        }

        return $instance;
    }
}
