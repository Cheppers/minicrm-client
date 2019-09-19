<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Project;

use Cheppers\MiniCrm\DataTypes\RequestBase;

class ProjectRequest extends RequestBase
{
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
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = [];

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'categoryId':
                    $data['CategoryId'] = $this->categoryId;
                    break;
                case 'contactId':
                    $data['ContactId'] = $this->contactId;
                    break;
                case 'name':
                    $data['Name'] = $this->name;
                    break;
            }
        }

        return $data;
    }
}
