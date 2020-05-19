<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class ProjectStatusUpdateRequest extends RequestBase
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var array
     */
    public $additionalProperties = [];

    /**
    * @inheritDoc
    */
    public function jsonSerialize()
    {

        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            if ($key !== 'additionalProperties') {
                continue;
            }

            foreach ($this->additionalProperties as $item => $value) {
                $data[ucfirst($item)] = $value;
            }
        }

        return $data;
    }
}
