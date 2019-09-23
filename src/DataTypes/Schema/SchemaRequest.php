<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Schema;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class SchemaRequest extends RequestBase
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        return $data;
    }
}
