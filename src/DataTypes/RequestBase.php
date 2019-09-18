<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes;

abstract class RequestBase implements \JsonSerializable
{
    public static function __set_state($data)
    {
        $instance = new static();

        foreach (array_keys(get_object_vars($instance)) as $key) {
            if (!array_key_exists($key, $data)) {
                continue;
            }
            $instance->{$key} = $data[$key];
        }

        return $instance;
    }
}
