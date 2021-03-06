<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Template;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class TemplateItem extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'Type' => 'type',
        'Name' => 'name',
        'Url' => 'url',
    ];

    /**
     * {@inheritdoc}
     */
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

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;
}
