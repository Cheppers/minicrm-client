<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class SingleProjectResponse extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'CategoryId' => 'categoryId',
        'ContactId' => 'contactId',
        'UserId' => 'userId',
        'Name' => 'name',
        'StatusUpdatedAt' => 'statusUpdatedAt',
        'Deleted' => 'deleted',
        'CreatedBy' => 'createdBy',
        'CreatedAt' => 'createdAt',
        'UpdatedBy' => 'updatedBy',
        'UpdatedAt' => 'updatedAt',
        'ProjectHash' => 'projectHash',
        'ProjectEmail' => 'projectEmail',
        'Url' => 'url',
        'StatusId' => 'statusId',
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
    public $categoryId;

    /**
     * @var int
     */
    public $contactId;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $deleted;

    /**
     * @var string
     */
    public $statusUpdatedAt;

    /**
     * @var string
     */
    public $createdBy;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $updatedBy;

    /**
     * @var string
     */
    public $updatedAt;

    /**
     * @var string
     */
    public $projectHash;

    /**
     * @var string
     */
    public $projectEmail;

    /**
     * @var string
     */
    public $url;

    /**
     * @var int
     */
    public $statusId;
}
