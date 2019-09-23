<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Todo;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class TodoRequest extends RequestBase
{
    /**
     * @var string
     */
    public $name;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'name':
                    $data['Name'] = $this->name;
                    break;
            }
        }

        return $data;
    }
}
