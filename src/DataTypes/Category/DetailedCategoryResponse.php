<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Category;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class DetailedCategoryResponse extends ResponseBase
{
    public static function __set_state($data)
    {
        $instance = new static();

        foreach ($data as $key => $element) {
            $instance->{$key} = DetailedCategoryItem::__set_state($element);
        }

        return $instance;
    }
}
