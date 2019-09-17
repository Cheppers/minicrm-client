<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes;

class Base
{

    /**
     * @var array
     */
    protected static $propertyMapping = [];

    public static function __set_state($data)
    {
        $instance = new static();

        foreach (static::$propertyMapping as $external => $internal) {
            if (!property_exists($instance, $internal)
                || !array_key_exists($external, $data)) {
                continue;
            }

            $instance->{$internal} = $data[$external];
        }

        return $instance;
    }
}
