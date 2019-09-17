<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Category;

use Cheppers\MiniCrm\DataTypes\Base;

class CategoriesDetailed extends Base
{
    public static function __set_state($data) {
        $instance = new static();

        foreach ($data as $key => $element) {
            $instance->{$key} = CategoryItem::__set_state($element);
        }

        return $instance;
    }
}
