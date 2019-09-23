<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Schema;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class SchemaResponse extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    public static function __set_state($data)
    {
        $instance = new static();

        foreach ($data as $key => $element) {
            $instance->results{$key} = $element;
        }

        return $instance;
    }

    /**
     * @var array
     */
    public $results;
}
