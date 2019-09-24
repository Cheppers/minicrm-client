<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class ProjectEmailItem extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    protected static $propertyMapping = [
        'Id' => 'id',
        'TemplateId' => 'templateId',
        'From' => 'from',
        'To' => 'to',
        'Subject' => 'subject',
        'Body' => 'body',
        'Status' => 'status',
        'CreatedAt' => 'createdAt',
        'OpenedAt' => 'openedAt',
        'ClickedAt' => 'clickedAt',
        'UnsubscribedDate' => 'unsubscribedDate',
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
    public $templateId;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $openedAt;

    /**
     * @var string
     */
    public $clickedAt;

    /**
     * @var string
     */
    public $unsubscribedDate;
}
