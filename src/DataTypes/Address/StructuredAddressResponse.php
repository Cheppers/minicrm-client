<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Address;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class StructuredAddressResponse extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    public static function __set_state($data)
    {
        $instance = new static();

        $keys = [
            'Results',
            'Count',
        ];

        foreach ($keys as $key) {
            $instance->{lcfirst($key)} = $data[$key];

            if (array_key_exists($key, $data) && is_array($data[$key])) {
                foreach ($data[$key] as $addressId => $result) {
                    if (is_array($result)) {
                        $instance->{lcfirst($key)}[$addressId] = StructuredAddressItem::__set_state($result);
                    }
                }
            }
        }

        return $instance;
    }

    /**
     * @var array
     */
    public $results;

    /**
     * @var int
     */
    public $count;
}
