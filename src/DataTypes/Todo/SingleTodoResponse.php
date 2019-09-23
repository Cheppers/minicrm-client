<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Todo;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class SingleTodoResponse extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'Status' => 'status',
        'Comment' => 'comment',
        'Deadline' => 'deadline',
        'UserId' => 'userId',
        'Type' => 'type',
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
    public $status;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $deadline;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $url;
}
