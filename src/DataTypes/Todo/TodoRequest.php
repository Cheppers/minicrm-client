<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Todo;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class TodoRequest extends RequestBase
{
    /**
     * @var int
     */
    public $projectId;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $deadline;

    /**
     * @var int|string
     */
    public $userId;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'projectId':
                    $data['ProjectId'] = $this->projectId;
                    break;
                case 'comment':
                    $data['Comment'] = $this->comment;
                    break;
                case 'deadline':
                    $data['Deadline'] = $this->deadline;
                    break;
                case 'userId':
                    $data['UserId'] = $this->userId;
                    break;
            }
        }

        return $data;
    }
}
