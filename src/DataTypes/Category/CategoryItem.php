<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Category;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class CategoryItem extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'OrderId' => 'orderId',
        'Name' => 'name',
        'Type' => 'type',
        'SenderName' => 'senderName',
        'SenderEmail' => 'senderEmail',
        'Phone' => 'phone',
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $orderId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $senderName;

    /**
     * @var string
     */
    public $senderEmail;

    /**
     * @var string
     */
    public $phone;
}
