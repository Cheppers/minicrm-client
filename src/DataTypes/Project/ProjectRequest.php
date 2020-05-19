<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class ProjectRequest extends RequestBase
{

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
    public $name;

    /**
     * @var array
     */
    public $additionalProperties = [];

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'id':
                    if (!is_null($this->id)) {
                        $data['Id'] = $this->id;
                    }
                    break;
                case 'categoryId':
                    $data['CategoryId'] = $this->categoryId;
                    break;
                case 'contactId':
                    $data['ContactId'] = $this->contactId;
                    break;
                case 'name':
                    $data['Name'] = $this->name;
                    break;
                case 'additionalProperties':
                    if (!empty($this->additionalProperties)) {
                        foreach ($this->additionalProperties as $item => $value) {
                            $data[ucfirst($item)] = $value;
                        }
                    }
                    break;
            }
        }

        return $data;
    }
}
