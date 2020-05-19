<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class ProjectResponse extends ResponseBase
{
    /**
     * {@inheritdoc}
     */
    public static function __set_state($data)
    {
        $instance = new static();

        $keys = [
            'Results',
            'Count',
        ];

        foreach ($keys as $key) {
//            if (array_key_exists($key, $data) && is_array($data[$key])) {
            if (array_key_exists($key, $data)) {
                $instance->{lcfirst($key)} = $data[$key];
                foreach ($data[$key] as $projectId => $result) {
                    if (is_array($result)) {
                        $instance->{lcfirst($key)}[$projectId] = SimpleProjectItem::__set_state($result);
                    }
                }
            }
        }

        return $instance;
    }

    /**
     * @var array
     */
    public $results;

    /**
     * @var int
     */
    public $count;
}
