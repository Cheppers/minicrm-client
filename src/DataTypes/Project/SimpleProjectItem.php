<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class SimpleProjectItem extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'ContactId' => 'contactId',
        'Name' => 'name',
        'Url' => 'url',
        'StatusId' => 'statusId',
        'UserId' => 'userId',
        'Deleted' => 'deleted',
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
     * @var int
     */
    public $contactId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;

    /**
     * @var int
     */
    public $statusId;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var int
     */
    public $deleted;
}
