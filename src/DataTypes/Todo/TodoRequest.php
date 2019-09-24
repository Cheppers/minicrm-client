<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Todo;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class TodoRequest extends RequestBase
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $projectId;

    /**
     * @var string
     */
    public $comment;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'id':
                    $data['TodoId'] = $this->id;
                    break;
                case 'projectId':
                    $data['ProjectId'] = $this->projectId;
                    break;
                case 'comment':
                    $data['Comment'] = $this->comment;
                    break;
            }
        }

        return $data;
    }
}
