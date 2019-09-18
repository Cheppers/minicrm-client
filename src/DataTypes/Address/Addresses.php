<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Address;

use Cheppers\MiniCrm\DataTypes\Base;

class Addresses extends Base
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Results' => 'results',
        'Count' => 'count',
    ];

    /**
     * @var array
     */
    public $results;

    /**
     * @var int
     */
    public $count;
}
