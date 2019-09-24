<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\ResponseBase;

class ProjectEmailsResponse extends ResponseBase
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
            $instance->{lcfirst($key)} = $data[$key];

            if (array_key_exists($key, $data) && is_array($data[$key])) {
                foreach ($data[$key] as $projectId => $result) {
                    if (is_array($result)) {
                        $instance->{lcfirst($key)}[$projectId] = ProjectEmailItem::__set_state($result);
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
